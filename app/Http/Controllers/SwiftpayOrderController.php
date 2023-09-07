<?php

namespace App\Http\Controllers;

use App\Http\Resources\SwiftpayOrderResource;
use App\Models\SwiftpayOrder;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class SwiftpayOrderController extends Controller
{
    public function index(Request $request): ResourceCollection
    {
        $user = auth()->user();
        $swiftpayOrders = new SwiftpayOrder();
        if ($user->tenant_id !== 'admin') {
            $swiftpayOrders = $swiftpayOrders->where('tenant_id', $user->tenant_id);
        }
        $swiftpayOrders = $swiftpayOrders->orderBy('created_at', 'desc')->paginate();
        return SwiftpayOrderResource::collection($swiftpayOrders);
    }
}
