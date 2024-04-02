<?php

namespace App\Http\Controllers\Gateway;

use Illuminate\Support\Facades\Log;

class GatewayDashboardController
{
    public function view()
    {
        Log::channel('wallet')->info('redirect to gateway dashboard');
        return view('gateway.dashboard');
    }
}
