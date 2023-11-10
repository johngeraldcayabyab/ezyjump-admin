<?php

namespace App\Http\Resources;

use App\Traits\ResourceTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SwiftpayCallbackResource extends JsonResource
{
    use ResourceTrait;

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'created_at' => $this->dateReadable($this->created_at),
            'reference_id' => $this->reference_id,
            'status' => $this->status,
        ];
    }
}
