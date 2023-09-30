<?php

namespace App\Http\Controllers;

use App\Http\Resources\SwiftpayQueryOrderResource;
use App\Models\SwiftpayQueryOrder;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SwiftpayQueryOrderController extends Controller
{
    public function index(Request $request): ResourceCollection
    {
        $user = auth()->user();
        $swiftpayQueryOrder = new SwiftpayQueryOrder();
        $swiftpayQueryOrder = $swiftpayQueryOrder->where('created_at', '>=', Carbon::now()->subDays(7)->startOfDay());
        if ($user->tenant_id !== 'admin') {
            $swiftpayQueryOrder = $swiftpayQueryOrder->where('tenant_id', $user->tenant_id);
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
        if (Str::contains($value, ',')) {
            $value = explode(',', $value);
        }
        if (is_array($value)) {
            $swiftpayQueryOrder = $swiftpayQueryOrder->whereIn($field, $value);
        } else {
            $swiftpayQueryOrder = $swiftpayQueryOrder->where($field, $value);
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
            ->orderBy('created_at', 'desc')
            ->paginate();
        return SwiftpayQueryOrderResource::collection($swiftpayQueryOrder);
    }

    public function statistics(): JsonResponse
    {
//        $yesterdayStart = Carbon::yesterday('Asia/Manila')->startOfDay();
//        $yesterdayEnd = Carbon::yesterday('Asia/Manila')->endOfDay();

//        $todayStart = Carbon::today('Asia/Manila')->startOfDay();
//        $todayEnd = Carbon::today('Asia/Manila')->endOfDay();

//        $start_of_day = Carbon::today('Asia/Manila');
//        $end_of_day = Carbon::today('Asia/Manila')->endOfDay();

//        $totalAmountYesterday = SwiftpayQueryOrder::whereBetween('created_at', [$yesterdayStart, $yesterdayEnd])->sum('amount');
//        $totalAmountToday = SwiftpayQueryOrder::whereBetween('created_at', [$start_of_day->timezone('UTC')->toDateTimeString(), $end_of_day->timezone('UTC')->toDateTimeString()])->sum('amount');

//        info(number_format($totalAmountYesterday));
//        info(number_format($totalAmountToday));

//        $date = Carbon::create(2023, 9, 14, 0, 0, 0, 'Asia/Manila');
//
//        $data = SwiftpayQueryOrder::whereDate(DB::raw('CONVERT_TZ(created_at, \'+00:00\', \'+08:00\')'), $date)->sum('amount');
//        info($data);


// Get yesterday's total amount
//        $yesterday = Carbon::yesterday('Asia/Manila')->setTimezone('UTC');
//        $yesterdayTotalAmount = SwiftpayQueryOrder::whereDate(DB::raw('CONVERT_TZ(created_at, \'+00:00\', \'+08:00\')'), $yesterday)->where('ORDER_STATUS', 'EXECUTED')->sum('amount');
//
//// Get today's total amount
//        $today = Carbon::today('Asia/Manila')->setTimezone('UTC');
//        $todayTotalAmount = SwiftpayQueryOrder::whereDate(DB::raw('CONVERT_TZ(created_at, \'+00:00\', \'+08:00\')'), $today)->where('ORDER_STATUS', 'EXECUTED')->sum('amount');
//
//        info($yesterdayTotalAmount);
//        info($todayTotalAmount);


//        $yesterdayStart = now()->setTimezone('UTC')->startOfDay();
//        $yesterdayEnd = now()->setTimezone('UTC')->endOfDay();

//        info($yesterdayStart);
//        info($yesterdayEnd);

//        $todayStart = Carbon::yesterday('UTC')->startOfDay();
//        $todayEnd = Carbon::today('Asia/Manila')->endOfDay()->setTimezone('UTC');

//        $totalAmountYesterday = SwiftpayQueryOrder::whereBetween('created_at', [$yesterdayStart, $yesterdayEnd])->sum('amount');

//        $totalAmountToday = SwiftpayQueryOrder::whereBetween('created_at', [$todayStart, $todayEnd])->where('ORDER_STATUS', 'EXECUTED')->sum('amount');


//        info(Carbon::yesterday('UTC')->startOfDay());
//        info($totalAmountToday);

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
