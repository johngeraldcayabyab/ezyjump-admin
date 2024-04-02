<?php

namespace App\Http\Controllers\Gateway;

use Illuminate\Support\Facades\Log;

class GatewayDashboardController
{
    public function view()
    {
        return view('gateway.dashboard');
    }
}
