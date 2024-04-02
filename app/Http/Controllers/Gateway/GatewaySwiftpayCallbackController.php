<?php

namespace App\Http\Controllers\Gateway;

use App\Facades\Authy;
use App\Http\Controllers\Controller;
use App\Http\Resources\SwiftpayCallbackResource;
use App\Models\SwiftpayCallback;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Str;

class GatewaySwiftpayCallbackController extends Controller
{
    public function index(Request $request): ResourceCollection
    {
        $user = Authy::user();
        $swiftpayCallback = new SwiftpayCallback();
        if (!$user) {
            return SwiftpayCallbackResource::collection($swiftpayCallback->where('id', 0)->cursorPaginate(15));
        }
        if (!$user->isAdmin()) {
            $swiftpayCallback = $swiftpayCallback->tenantId($user->getTenantIds());
        }
        $field = null;
        $value = null;
        if ($request->field === 'id') {
            $field = 'id';
            $value = $request->value;
        }
        if ($request->field === 'reference_id') {
            $field = 'reference_id';
            $value = $request->value;
        }
        $value = Str::replace(' ', '', $value);
        if (strlen($value)) {
            if (Str::contains($value, ',')) {
                $value = explode(',', $value);
            }
            if (is_array($value)) {
                $swiftpayCallback = $swiftpayCallback->whereIn($field, $value);
            } else {
                $swiftpayCallback = $swiftpayCallback->where($field, $value);
            }
        }
        $swiftpayCallback = $swiftpayCallback
            ->select(
                'id',
                'reference_id',
                'created_at',
                'delivery_count',
                'status',
            )
            ->orderBy('created_at', 'desc')->cursorPaginate(15);
        return SwiftpayCallbackResource::collection($swiftpayCallback);
    }
}
