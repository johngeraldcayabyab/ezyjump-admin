<?php

namespace App\Http\Controllers\Gateway;

use App\Http\Controllers\Controller;

class GatewayDashboardController extends Controller
{
    public function view()
    {
        return view('gateway.dashboard');
    }
}
