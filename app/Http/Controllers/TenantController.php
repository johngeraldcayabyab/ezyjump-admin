<?php

namespace App\Http\Controllers;

use App\Http\Resources\TenantResource;
use App\Models\Tenant;
use Illuminate\Http\Request;

class TenantController extends Controller
{
    public function expose(Request $request)
    {
        $tenant = new Tenant();
        if ($request->bearerToken() !== 'base64:Ae7IVeO994zF+km9v1CD54b42zKBuu/8mA55dxWktRA=') {
            $tenant = $tenant->where('id', 0)->get();
            return TenantResource::collection($tenant);
        }
        if ($request->channel) {
            $tenant->where('channel', $request->channel);
        }
        $tenant = $tenant->get();
        return TenantResource::collection($tenant);
    }
}
