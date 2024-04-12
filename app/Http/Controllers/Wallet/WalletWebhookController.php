<?php

namespace App\Http\Controllers\Wallet;

use App\Facades\Authy;
use App\Http\Controllers\Controller;
use App\Http\Resources\WalletWebhookResource;
use App\Models\WalletWebhook;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Str;
use Illuminate\View\View;

class WalletWebhookController extends Controller
{
    public function view(): View
    {
        return view('wallet.webhook');
    }

    public function index(Request $request): ResourceCollection
    {
        $user = Authy::user();
        $meta = session('user_metadata');
        $webhook = new WalletWebhook();
        if (!$user) {
            return WalletWebhookResource::collection($webhook->where('id', 0)->cursorPaginate(15));
        }
        if (!in_array('DASHBOARD_ADMIN', $meta['permissions'])) {
            $webhook = $webhook->where('merchant_id', $user->id);
        }
        $field = null;
        $value = null;
        if ($request->field === 'id') {
            $field = 'id';
            $value = $request->value;
        }
        if ($request->field === 'entity_id') {
            $field = 'entity_id';
            $value = $request->value;
        }
        $value = Str::replace(' ', '', $value);
        if (strlen($value)) {
            if (Str::contains($value, ',')) {
                $value = explode(',', $value);
            }
            if (is_array($value)) {
                $webhook = $webhook->whereIn($field, $value);
            } else {
                $webhook = $webhook->where($field, $value);
            }
        }
        $status = trim($request->status);
        if ($status && $status !== 'ALL') {
            $webhook = $webhook->where('status', $status);
        }
        $webhook = $webhook->createdAtBetween($request->dateFrom, $request->dateTo);
        $webhook = $webhook
            ->select(
                'id',
                'entity_id',
                'retry_count',
                'status',
                'created_at',
            )
            ->orderBy('created_at', 'desc');
        $webhook = $webhook->cursorPaginate(15);
        return WalletWebhookResource::collection($webhook);
    }


//    public function retry(Request $request)
//    {
//        $referenceId = $request->reference_id;
//        $data = ['data' => $referenceId];
//        $swiftpayCallback = SwiftpayCallback::where('reference_id', $referenceId)->first();
//        if (!$swiftpayCallback) {
//            return ['status' => 'error', 'message' => "$referenceId No. Does not exist"];
//        }
//        $token = config('tokens.EZYJUMP_TOKEN');
//        $token = str_replace('Bearer', '', $token);
//        $token = trim($token);
//        $user = Authy::user();
//        if (!$user) {
//            return ['status' => 'error', 'message' => 'Not authenticated!'];
//        }
//        if (!$user->isAdmin()) {
//            $tenant = Tenant::where('tenant_id', $swiftpayCallback->tenant_id)->first();
//            if (!$tenant) {
//                return ['status' => 'error', 'message' => 'Authorization token¬ does not exists'];
//            }
//            $token = $tenant->authorization_token;
//        }
//        info($token);
//        info("retry callback ref no " . $referenceId);
//        $bearerToken = "Bearer $token";
//        if ((int)$swiftpayCallback->delivery_count >= 3) {
//            return ['status' => 'error', 'message' => 'Callbacks retry have been exceeded, please contact the admin.'];
//        }
//        try {
//            $client = new Client([
//                'base_uri' => 'https://api.ezyjump-pay.com'
//            ]);
//            $response = $client->put('/api/callbacks/retry?status=SUCCESS', [
//                'headers' => [
//                    'Authorization' => $bearerToken,
//                    'Content-Type' => 'application/json'
//                ],
//                'json' => $data
//            ]);
//            $retryStatus = $response->getStatusCode();
//            info("callback retry status " . $referenceId . " " . $retryStatus);
//            return response()->json(['retry_status' => $retryStatus]);
//        } catch (Exception $exception) {
//            $message = $exception->getMessage();
//            Log::error($message);
//            return ['status' => 'error', 'message' => $message];
//        }
//    }
}
