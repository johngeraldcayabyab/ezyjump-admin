<?php

namespace App\Http\Resources;

use App\Traits\ResourceTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GcashSmsResource extends JsonResource
{
    use ResourceTrait;

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'amount' => $this->amount,
            'created_at' => $this->dateReadable($this->created_at),
            'date_received' => $this->dateReadable($this->date_received),
            'is_used' => $this->is_used,
            'updated_at' => $this->dateReadable($this->updated_at),
            'version' => $this->version,
            'channel' => $this->channel,
        ];
    }
}
