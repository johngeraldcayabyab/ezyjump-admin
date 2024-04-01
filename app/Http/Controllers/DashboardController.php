<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;

class DashboardController
{
    public function view()
    {
        Log::channel('wallet')->info('FUCK WRONG REDIRECT');
        return view('dashboard');
    }
}
