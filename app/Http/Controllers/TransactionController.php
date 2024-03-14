<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class TransactionController extends Controller
{
    public function show(): View
    {
        return view('transactions');
    }

    public function swiftView(): View
    {
        return view('swiftpay-transactions');
    }

    public function swiftQrView(): View
    {
        return view('swiftpay-qr-transactions');
    }
}
