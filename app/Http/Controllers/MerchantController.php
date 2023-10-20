<?php

namespace App\Http\Controllers;

use App\Models\Merchant;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MerchantController
{
    public function show(): View
    {
        $merchants = Merchant::all();
        return view('merchants', ['merchants' => $merchants]);
    }

    public function toggle(Request $request)
    {
        return response()->json(['message' => 'toggled', 'data' => $request->all()]);
    }
}
