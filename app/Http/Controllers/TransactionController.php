<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class TransactionController extends Controller
{
    public function show(): View
    {
        return view('transactions');
    }
}
