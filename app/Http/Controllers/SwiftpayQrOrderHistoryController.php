<?php

namespace App\Http\Controllers;

use App\Http\Resources\SwiftpayQrOrderHistoryResource;
use App\Models\SwiftpayQrOrderHistory;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class SwiftpayQrOrderHistoryController extends Controller
{
    public function index(Request $request): ResourceCollection
    {
        $user = auth()->user();
        $swiftpayQrOrderHistory = new SwiftpayQrOrderHistory();
        if (!$user) {
            return SwiftpayQrOrderHistoryResource::collection($swiftpayQrOrderHistory->where('id', 0)->cursorPaginate(15));
        }
        if (!$user->isAdmin()) {
            $swiftpayQrOrderHistory = $swiftpayQrOrderHistory->tenantId($user->getTenantIds());
        }
        $field = $request->field;
        $value = Str::replace(' ', '', $request->value);
        if (Str::contains($value, ',')) {
            $value = explode(',', $value);
        }
        if ($value) {
            $swiftpayQrOrderHistory = $swiftpayQrOrderHistory->whereIn($field, Arr::wrap($value));
        }
        $status = trim($request->status);
        if ($status && $status !== 'ALL') {
            $swiftpayQrOrderHistory = $swiftpayQrOrderHistory->where('status', $status);
        }
        $swiftpayQrOrderHistory = $swiftpayQrOrderHistory->createdAtBetween($request->dateFrom, $request->dateTo);
        $swiftpayQrOrderHistory = $swiftpayQrOrderHistory
            ->select(
                'id',
                'created_at',
                'transaction_id',
                'status',
                'amount'
            )
            ->orderBy('created_at', 'desc');
        $swiftpayQrOrderHistory = $swiftpayQrOrderHistory->cursorPaginate(15);
        return SwiftpayQrOrderHistoryResource::collection($swiftpayQrOrderHistory);
    }
}
