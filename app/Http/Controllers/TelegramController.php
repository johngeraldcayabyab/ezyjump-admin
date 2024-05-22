<?php

namespace App\Http\Controllers;

use App\Jobs\TelegramProcess;
use Illuminate\Http\Request;


class TelegramController
{
    public function receiver(Request $request)
    {
        TelegramProcess::dispatch($request->all());
    }
}
