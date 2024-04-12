<?php

namespace App\Http\Resources;

use App\Traits\ResourceTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WalletWebhookResource extends JsonResource
{
    use ResourceTrait;

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'entity_id' => $this->entity_id,
            'retry_count' => $this->retry_count,
            'status' => $this->status,
            'created_at' => $this->dateReadable($this->created_at)
        ];
    }
}
