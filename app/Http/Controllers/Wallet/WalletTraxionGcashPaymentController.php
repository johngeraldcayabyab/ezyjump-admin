<?php

namespace App\Http\Controllers\Wallet;

use Illuminate\View\View;

class WalletTraxionGcashPaymentController
{
    public function view(): View
    {
        return view('wallet.traxion-gcash-payment');
    }
}
