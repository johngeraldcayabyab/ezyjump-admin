<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function dateFrom($dateFrom)
    {
        if (Carbon::hasFormat($dateFrom, 'Y-m-d')) {
            return Carbon::parse($dateFrom)->startOfDay()->subHours(8);
        }
        return Carbon::today()->timezone('Asia/Manila')->startOfDay()->subHours(8);
    }

    public function dateTo($dateTo)
    {
        if (Carbon::hasFormat($dateTo, 'Y-m-d')) {
            return Carbon::parse($dateTo)->endOfDay()->subHours(8);
        }
        return now()->timezone('Asia/Manila');
    }
}
