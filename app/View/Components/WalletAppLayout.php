<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class WalletAppLayout extends Component
{
    public function render(): View
    {
        return view('layouts.wallet-app');
    }
}
