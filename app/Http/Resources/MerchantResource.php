<?php

namespace App\Http\Resources;

use App\Traits\ResourceTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MerchantResource extends JsonResource
{
    use ResourceTrait;

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'tenant_id' => $this->tenant_id,
            'channel' => $this->channel,
            'authorization_token' => $this->authorization_token,
            'credentials' => $this->credentials,
            'created_at' => $this->dateReadable($this->created_at),
            'updated_at' => $this->dateReadable($this->updated_at),
        ];
    }
}
