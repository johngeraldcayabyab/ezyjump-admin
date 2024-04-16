<?php

namespace App\Http\Controllers\Gateway;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class GatewayTransactionController extends Controller
{
    public function swiftView(): View
    {
        return view('gateway.swiftpay-transactions');
    }
}
