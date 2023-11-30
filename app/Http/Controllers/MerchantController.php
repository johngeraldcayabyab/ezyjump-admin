<?php

namespace App\Http\Controllers;

use App\Http\Resources\MerchantResource;
use App\Models\Merchant;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MerchantController extends Controller
{
    public function show(): View
    {
        $merchants = Merchant::all();
        return view('merchants', ['merchants' => $merchants]);
    }

    public function index(Request $request)
    {
        $merchant = new Merchant();
        if ($request->channel) {
            $merchant->where('channel', $request->channel);
        }
        $merchant = $merchant->get();
        return MerchantResource::collection($merchant);
    }

    public function toggle(Request $request)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['message' => 'unauthorized']);
        }
        $merchant = Merchant::find($request->id);
        $merchant->enabled = !$merchant->enabled;
        $merchant->save();
        return response()->json(['message' => 'toggled', 'data' => $merchant]);
    }
}
