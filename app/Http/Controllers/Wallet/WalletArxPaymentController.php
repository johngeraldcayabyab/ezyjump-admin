<?php

namespace App\Http\Controllers\Wallet;

use App\Facades\Authy;
use App\Http\Controllers\Controller;
use App\Http\Resources\WalletArxPaymentResource;
use App\Models\WalletArxPayment;
use App\Models\WalletMerchant;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\View\View;

class WalletArxPaymentController extends Controller
{
    public function view(): View
    {
        return view('wallet.arx-payment');
    }

    public function sync(Request $request)
    {
        $id = $request->id;
        $arxPayment = WalletArxPayment::find($id);
        if (!$arxPayment) {
            return ['status' => 'error', 'message' => "$id does not exist"];
        }
        $paymentId = $arxPayment->payment_id;
        $data = ['data' => [$paymentId]];
        $user = Authy::user();
        $meta = session('user_metadata');
        if (!$user) {
            return ['status' => 'error', 'message' => 'Not authenticated!'];
        }
        if (!in_array('DASHBOARD_ADMIN', $meta['permissions'])) {
            if (Cache::has("sync_$paymentId")) {
                return ['status' => 'error', 'message' => 'Sync again in 5 minutes!'];
            }
            if (!in_array('CASH_IN_SYNC', $meta['permissions'])) {
                return ['status' => 'error', 'message' => "You don't have permission to sync!"];
            }
        }
        $merchant = WalletMerchant::where('name', 'EZYJUMP-ADMIN')->first();
        $merchantKey = $merchant->merchantKey;
        $token = $merchantKey->api_key;
        Log::channel('wallet')->info("sync id: " . $paymentId);
        $path = null;
        if ($request->entity_type === 'ASUKA_CASHIN') {
            $path = '/api/asuka/cashins/sync';
        } else if ($request->entity_type === 'TIDUS_CASHIN') {
            $path = '/api/cashins/sync';
        }
        if (!$path) {
            return ['status' => 'error', 'message' => "Entity type does not exist!"];
        }
        try {
            $client = new Client([
                'base_uri' => 'https://api.ipaygames.com'
            ]);
            $response = $client->patch($path, [
                'headers' => [
                    'X-API-KEY' => $token,
                    'Content-Type' => 'application/json'
                ],
                'json' => $data
            ]);
            $syncStatus = $response->getStatusCode();
            if ($syncStatus === 200) {
                Cache::put("sync_$paymentId", $paymentId, now()->addMinutes(5));
            }
            Log::channel('wallet')->info("sync status " . $paymentId . " " . $syncStatus);
            return response()->json(['sync_status' => $syncStatus]);
        } catch (Exception $exception) {
            $message = $exception->getMessage();
            Log::channel('wallet')->error($message);
            return ['status' => 'error', 'message' => $message];
        }
    }

    public function index(Request $request): ResourceCollection
    {
        $user = Authy::user();
        $meta = session('user_metadata');
        $arxPayment = new WalletArxPayment();
        if (!$user) {
            return WalletArxPaymentResource::collection($arxPayment->where('id', 0)->cursorPaginate(15));
        }
        if (!in_array('DASHBOARD_ADMIN', $meta['permissions'])) {
            $arxPayment = $arxPayment->where('merchant_id', $user->id);
        }
        $field = null;
        $value = null;
        if ($request->field === 'transaction_id') {
            $field = 'transaction_id';
            $value = $request->value;
        }
        if ($request->field === 'order_id') {
            $field = 'order_id';
            $value = $request->value;
        }
        if ($request->field === 'gcash_reference_number') {
            $field = 'gcash_reference_number';
            $value = $request->value;
        }
        $value = Str::replace(' ', '', $value);
        if (strlen($value)) {
            if (Str::contains($value, ',')) {
                $value = explode(',', $value);
            }
            if (is_array($value)) {
                $arxPayment = $arxPayment->whereIn($field, $value);
            } else {
                $arxPayment = $arxPayment->where($field, $value);
            }
        }
        $status = trim($request->status);
        if ($status && $status !== 'ALL') {
            $arxPayment = $arxPayment->where('arx_status', $status);
        }
        $arxPayment = $arxPayment->createdAtBetween($request->dateFrom, $request->dateTo);
        $arxPayment = $arxPayment
            ->select(
                'id',
                'created_at',
                'order_id',
                'transaction_id',
                'amount',
                'gcash_reference_number',
                'arx_status',
            )
            ->orderBy('id', 'desc');
        $arxPayment = $arxPayment->cursorPaginate(15);
        return WalletArxPaymentResource::collection($arxPayment);
    }
}
