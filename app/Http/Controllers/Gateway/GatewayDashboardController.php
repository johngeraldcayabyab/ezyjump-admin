<?php

namespace App\Http\Controllers\Gateway;

use Illuminate\Support\Facades\Log;

class GatewayDashboardController
{
    public function view()
    {
        Log::channel('wallet')->info('FUCK WRONG REDIRECT');
        return view('gateway.dashboard');
    }
}
