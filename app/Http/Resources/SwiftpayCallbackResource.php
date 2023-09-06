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
            'tenant_id' => $this->tenant_id,
            'updated_at' => $this->dateReadable($this->updated_at),
            'version' => $this->version,
            'callback_url' => $this->callback_url,
            'delivery_count' => $this->delivery_count,
            'event_type' => $this->event_type,
            'reference_id' => $this->reference_id,
            'routing_key' => $this->routing_key,
            'serialized_event' => $this->serialized_event,
            'status' => $this->status,
            'target_exchange' => $this->target_exchange,
        ];
    }
}
