<?php

namespace App\Http\Controllers;

use App\Http\Resources\SwiftpayOrderResource;
use App\Models\SwiftpayOrder;
use Illuminate\Http\Resources\Json\ResourceCollection;

class SwiftpayOrderController extends Controller
{
    public function index(): ResourceCollection
    {
        $swiftpayOrders = SwiftpayOrder::orderBy('created_at', 'desc')->paginate();
        return SwiftpayOrderResource::collection($swiftpayOrders);
    }
}
