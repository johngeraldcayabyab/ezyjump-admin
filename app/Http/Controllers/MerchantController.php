<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class MerchantController
{
    public function show(): View
    {
        return view('merchants');
    }
}
