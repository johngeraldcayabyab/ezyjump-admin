<?php

namespace App\Http\Controllers;

use App\Http\Resources\SwiftpayQueryOrderResource;
use App\Models\SwiftpayQueryOrder;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

class SwiftpayQueryOrderController extends Controller
{
    public function index(Request $request): ResourceCollection
    {
        $user = auth()->user();
        $swiftpayQueryOrder = new SwiftpayQueryOrder();
        if ($user->tenant_id !== 'admin') {
            $swiftpayQueryOrder = $swiftpayQueryOrder->tenantId($user->tenant_id);
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
            $field = 'gcash_reference';
            $value = $request->value;
        }
        $value = Str::replace(' ', '', $value);
        if ($field === 'gcash_reference') {
            $dateFrom = Carbon::today()->timezone('Asia/Manila')->startOfDay()->subHours(8);
            $dateTo = now()->timezone('Asia/Manila');
            if (Carbon::hasFormat($request->dateFrom, 'Y-m-d') && Carbon::hasFormat($request->dateTo, 'Y-m-d')) {
                $dateFrom = Carbon::parse($request->dateFrom)->startOfDay()->subHours(8);
                $dateTo = Carbon::parse($request->dateT)->endOfDay()->subHours(8);
            }
            $status = trim($request->status);
            if (!in_array($status, ['PENDING', 'EXECUTED', 'CANCELED', 'REJECTED', 'EXPIRED'])) {
                $status = 'EXECUTED';
            }
            Artisan::call("app:fetch-swiftpay-ref-using-gcash-ref-command", [
                'dateFrom' => $dateFrom,
                'dateTo' => $dateTo,
                'gcashRef' => $value,
                'status' => $status,
            ]);
            $referenceNumber = trim(Artisan::output());
            $swiftpayQueryOrder = $swiftpayQueryOrder->where('reference_number', $referenceNumber);
        } else if (strlen($value)) {
            if (Str::contains($value, ',')) {
                $value = explode(',', $value);
            }
            if (is_array($value)) {
                $swiftpayQueryOrder = $swiftpayQueryOrder->whereIn($field, $value);
            } else {
                $swiftpayQueryOrder = $swiftpayQueryOrder->where($field, $value);
            }
        } else {
            $status = trim($request->status);
            if ($status && $status !== 'ALL') {
                $swiftpayQueryOrder = $swiftpayQueryOrder->where('order_status', $status);
            }
            $swiftpayQueryOrder = $swiftpayQueryOrder->createdAtBetween($request->dateFrom, $request->dateTo);
        }
        $swiftpayQueryOrder = $swiftpayQueryOrder
            ->select(
                'id',
                'created_at',
                'transaction_id',
                'reference_number',
                'order_status',
                'amount'
            )
            ->orderBy('id', 'desc')->cursorPaginate(15);
        return SwiftpayQueryOrderResource::collection($swiftpayQueryOrder);
    }

    public function statistics(): JsonResponse
    {
        $yesterdayStart = \Carbon\Carbon::yesterday('UTC')->startOfDay();
        $yesterdayEnd = \Carbon\Carbon::yesterday('UTC')->endOfDay();
        $totalAmountYesterday = SwiftpayQueryOrder::whereBetween('created_at', [$yesterdayStart, $yesterdayEnd])->where('ORDER_STATUS', 'EXECUTED')->sum('amount');

        info($totalAmountYesterday);

        return response()->json([
            'total_amount_yesterday' => 5000,
            'total_amount_today' => 10000,
        ]);
    }
}
