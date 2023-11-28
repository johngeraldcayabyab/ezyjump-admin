<?php

namespace App\Http\Controllers;

use App\Http\Resources\PayboritPaymentHistoryResource;
use App\Models\PayboritPaymentHistory;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Str;

class PayboritPaymentHistoryController extends Controller
{
    public function index(Request $request): ResourceCollection
    {
        $user = auth()->user();
        $payboritPaymentHistory = new PayboritPaymentHistory();
        if (!$user) {
            return PayboritPaymentHistoryResource::collection($payboritPaymentHistory->where('id', 0)->cursorPaginate(15));
        }
        if (!$user->isAdmin()) {
            $payboritPaymentHistory = $payboritPaymentHistory->tenantId($user->getTenantIds());
        }
        $field = null;
        $value = null;
        if ($request->field === 'transaction_id') {
            $field = 'transaction_id';
            $value = $request->value;
        }
        if ($request->field === 'payment_id') {
            $field = 'payment_id';
            $value = $request->value;
        }
        $value = Str::replace(' ', '', $value);
        if (strlen($value)) {
            if (Str::contains($value, ',')) {
                $value = explode(',', $value);
            }
            if (is_array($value)) {
                $payboritPaymentHistory = $payboritPaymentHistory->whereIn($field, $value);
            } else {
                $payboritPaymentHistory = $payboritPaymentHistory->where($field, $value);
            }
        } else {
            $status = trim($request->status);
            if ($status && $status !== 'ALL') {
                $payboritPaymentHistory = $payboritPaymentHistory->where('payment_status', $status);
            }
            $payboritPaymentHistory = $payboritPaymentHistory->createdAtBetween($request->dateFrom, $request->dateTo);
        }
        $payboritPaymentHistory = $payboritPaymentHistory
            ->select(
                'id',
                'created_at',
                'transaction_id',
                'payment_id',
                'payment_status',
                'updated_at',
                'amount'
            )
            ->orderBy('created_at', 'desc')->cursorPaginate(15);
        return PayboritPaymentHistoryResource::collection($payboritPaymentHistory);
    }
}
