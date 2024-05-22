<?php

namespace App\Http\Controllers\Wallet;

use App\Facades\Authy;
use App\Http\Controllers\Controller;
use App\Http\Resources\WalletArxPaymentResource;
use App\Jobs\ArxSync;
use App\Models\WalletArxPayment;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Cache;
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
        ArxSync::dispatch($paymentId, $request->entity_type);
        return ['sync_status' => 200];
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
        $arxPayment = $this->getIn($arxPayment, $field, $value);
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
