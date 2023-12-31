<?php

namespace App\Http\Controllers;

use App\Http\Resources\GcashPaymentResource;
use App\Models\GcashPayment;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Str;

class GcashPaymentController extends Controller
{
    public function index(Request $request): ResourceCollection
    {
        $user = auth()->user();
        $gcashPayment = new GcashPayment();
        if (!$user) {
            return GcashPaymentResource::collection($gcashPayment->where('id', 0)->cursorPaginate(15));
        }
        if (!$user->isAdmin()) {
            $gcashPayment = $gcashPayment->tenantId($user->getTenantIds());
        }
        $field = null;
        $value = null;
        if ($request->field === 'transaction_id') {
            $field = 'transaction_id';
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
                $gcashPayment = $gcashPayment->whereIn($field, $value);
            } else {
                $gcashPayment = $gcashPayment->where($field, $value);
            }
        } else {
            $status = trim($request->status);
            if ($status && $status !== 'ALL') {
                $gcashPayment = $gcashPayment->where('status', $status);
            }
            $gcashPayment = $gcashPayment->createdAtBetween($request->dateFrom, $request->dateTo);
        }
        $gcashPayment = $gcashPayment
            ->select(
                'id',
                'created_at',
                'transaction_id',
                'callback_url',
                'gcash_reference_number',
                'status',
                'updated_at',
                'amount',
            )
            ->orderBy('id', 'desc')->cursorPaginate(15);
        return GcashPaymentResource::collection($gcashPayment);
    }
}
