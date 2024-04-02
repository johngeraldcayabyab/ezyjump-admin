<?php

namespace App\Http\Controllers\Wallet;

use Illuminate\Support\Facades\Log;

class WalletDashboardController
{
    public function view()
    {
        return view('wallet.dashboard');
    }
}
