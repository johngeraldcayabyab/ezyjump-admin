<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CallbackReceiverController
{
    public function receiver(Request $request)
    {
        $all = $request->all();
        info('**This is a test callback receiver**');
        info($all);
        info('**This is a test callback receiver end**');
        return response(['status' => 200, 'content' => $all]);
    }
}
