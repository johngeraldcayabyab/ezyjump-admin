<?php

namespace App\Http\Controllers\Gateway;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class GatewayTransactionController extends Controller
{
    public function show(): View
    {
        return view('gateway.transactions');
    }

    public function swiftView(): View
    {
        return view('gateway.swiftpay-transactions');
    }

    public function swiftQrView(): View
    {
        return view('gateway.swiftpay-qr-transactions');
    }
}
