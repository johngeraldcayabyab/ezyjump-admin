<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CallbackReceiverController
{
    public function receiver(Request $request)
    {
        $all = $request->all();
        return response(['status' => 200, 'content' => $all]);
    }
}
