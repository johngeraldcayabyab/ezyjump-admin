<?php

namespace App\Http\Controllers;

use App\Http\Resources\SwiftPayOrderResource;
use App\Models\SwiftPayOrder;
use Illuminate\Http\Resources\Json\ResourceCollection;

class SwiftPayOrderController extends Controller
{
    public function index(): ResourceCollection
    {
        return SwiftPayOrderResource::collection(SwiftPayOrder::paginate());
    }
}
