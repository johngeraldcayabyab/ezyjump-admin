<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class TransactionController extends Controller
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
