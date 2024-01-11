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
//        return view('swift')
    }

    public function gcashView(): View
    {
        return view('gcash-transactions');
    }

    public function payboritView(): View
    {
        return view('payborit-transactions');
    }
}
