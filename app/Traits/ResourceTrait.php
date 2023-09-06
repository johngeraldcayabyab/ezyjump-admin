<?php

namespace App\Traits;

use Carbon\Carbon;

trait ResourceTrait
{
    public function dateReadable($date)
    {
        return Carbon::parse($date)->setTimezone('Asia/Manila')->format('D M j g:i A');
    }
}
