<?php

namespace App\Http\Controllers\Wallet;

use App\Facades\Authy;
use App\Http\Resources\WalletArxPaymentResource;
use App\Models\WalletArxPayment;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Str;
use Illuminate\View\View;

class WalletArxPaymentController
{
    public function view(): View
    {
        return view('wallet.arx-payment');
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
                'arx_status',
            )
            ->orderBy('id', 'desc');
        $arxPayment = $arxPayment->cursorPaginate(15);
        return WalletArxPaymentResource::collection($arxPayment);
    }
}
