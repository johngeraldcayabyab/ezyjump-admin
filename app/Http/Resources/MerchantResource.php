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
            'enabled' => $this->enabled,
            'name' => $this->name,
            'preferred_account' => $this->preferred_account,
            'version' => $this->version,
            'type' => $this->type,
            'created_at' => $this->dateReadable($this->created_at),
            'updated_at' => $this->dateReadable($this->updated_at),
            'tenant' => new TenantResource($this->whenLoaded('tenant')),
        ];
    }
}
