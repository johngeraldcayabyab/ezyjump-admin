<?php

namespace App\Http\Controllers\Wallet;

use App\Http\Controllers\Controller;

class WalletDashboardController extends Controller
{
    public function view()
    {
        return view('wallet.dashboard');
    }
}
