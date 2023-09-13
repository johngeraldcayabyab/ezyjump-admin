<?php

namespace App\Http\Controllers;

use App\Http\Resources\SwiftpayOrderResource;
use App\Models\SwiftpayOrder;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Str;

class SwiftpayOrderController extends Controller
{
    public function index(Request $request): ResourceCollection
    {
        $user = auth()->user();
        $swiftpayOrders = new SwiftpayOrder();
        if ($user->tenant_id !== 'admin') {
            $swiftpayOrders = $swiftpayOrders->where('tenant_id', $user->tenant_id);
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
        info($field);
        info($value);
        if (is_array($value)) {
            $swiftpayOrders = $swiftpayOrders->whereIn($field, $value);
        } else {
            $swiftpayOrders = $swiftpayOrders->where($field, $value);
        }
        $swiftpayOrders = $swiftpayOrders->orderBy('created_at', 'desc')->paginate();
        return SwiftpayOrderResource::collection($swiftpayOrders);
    }
}
