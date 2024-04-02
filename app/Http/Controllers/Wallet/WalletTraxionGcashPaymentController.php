<?php

namespace App\Http\Controllers\Wallet;

use App\Facades\Authy;
use App\Http\Resources\WalletTraxionGcashPaymentResource;
use App\Models\WalletTraxionGcashPayment;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\View\View;

class WalletTraxionGcashPaymentController
{
    public function view(): View
    {
        return view('wallet.traxion-gcash-payment');
    }

    public function index(Request $request): ResourceCollection
    {
        $user = Authy::user();
        $meta = session('user_metadata');
        $traxionGcashPayment = new WalletTraxionGcashPayment();
        if (!$user) {
            return WalletTraxionGcashPaymentResource::collection($traxionGcashPayment->where('id', 0)->cursorPaginate(15));
        }
        if (!Arr::has($meta['permissions'], 'DASHBOARD_ADMIN')) {
            $traxionGcashPayment = $traxionGcashPayment->where('merchant_id', $user->id);
        }
        $status = trim($request->status);
        if ($status && $status !== 'ALL') {
            $traxionGcashPayment = $traxionGcashPayment->whereHas('output', function ($query) use ($status) {
                $query->where('transaction_status', $status);
            })->with(['output' => function ($query) use ($status) {
                $query->where('transaction_status', $status);
            }]);
        } else {
            $traxionGcashPayment = $traxionGcashPayment->with('output');
        }
        $field = null;
        $value = null;
        if ($request->field === 'transaction_id') {
            $field = 'transaction_id';
            $value = $request->value;
        }
        if ($request->field === 'reference_number') {
            $field = 'reference_number';
            $value = $request->value;
        }
        if ($request->field === 'gcash_reference') {
            $field = 'third_party_reference_number';
            $value = $request->value;
        }
        $value = Str::replace(' ', '', $value);
        $value = Str::replace(' ', '', $value);
        if (strlen($value)) {
            if (Str::contains($value, ',')) {
                $value = explode(',', $value);
            }
            if (is_array($value)) {
                $traxionGcashPayment = $traxionGcashPayment->whereIn($field, $value);
            } else {
                $traxionGcashPayment = $traxionGcashPayment->where($field, $value);
            }
        }
        $traxionGcashPayment = $traxionGcashPayment->createdAtBetween($request->dateFrom, $request->dateTo);
        $traxionGcashPayment = $traxionGcashPayment
            ->select(
                'id',
                'created_at',
                'transaction_id',
                'reference_number',
            )
            ->orderBy('id', 'desc');
        $traxionGcashPayment = $traxionGcashPayment->cursorPaginate(15);
        return WalletTraxionGcashPaymentResource::collection($traxionGcashPayment);
    }
}
