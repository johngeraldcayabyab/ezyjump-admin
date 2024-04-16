<?php

namespace App\Http\Controllers\Gateway;

use App\Facades\Authy;
use App\Http\Controllers\Controller;
use App\Http\Resources\SwiftpayQrOrderHistoryResource;
use App\Models\SwiftpayQrOrderHistory;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class GatewaySwiftpayQrOrderHistoryController extends Controller
{
    public function index(Request $request): ResourceCollection
    {
        $user = Authy::user();
        $swiftpayQrOrderHistory = new SwiftpayQrOrderHistory();
        if (!$user) {
            return SwiftpayQrOrderHistoryResource::collection($swiftpayQrOrderHistory->where('id', 0)->cursorPaginate(15));
        }
        if (!$user->isAdmin()) {
            $swiftpayQrOrderHistory = $swiftpayQrOrderHistory->tenantId($user->getTenantIds());
        }
        $field = null;
        $value = null;
        if ($request->field === 'id') {
            $field = 'id';
            $value = $request->value;
        }
        if ($request->field === 'transaction_id') {
            $field = 'transaction_id';
            $value = $request->value;
        }
        $swiftpayQrOrderHistory = $this->getIn($swiftpayQrOrderHistory, $field, $value);
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
