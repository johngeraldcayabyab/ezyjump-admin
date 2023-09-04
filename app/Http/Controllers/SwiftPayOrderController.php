<?php

namespace App\Http\Controllers;

use App\Http\Resources\SwiftPayOrderResource;
use App\Models\SwiftPayOrder;
use Illuminate\Http\Resources\Json\ResourceCollection;

class SwiftPayOrderController extends Controller
{
    public function index(): ResourceCollection
    {
        $swiftPayOrders = SwiftPayOrder::orderBy('created_at', 'desc')->paginate();
        return SwiftPayOrderResource::collection($swiftPayOrders);
    }
}
