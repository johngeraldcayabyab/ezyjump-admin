<?php

namespace App\Http\Resources;

use App\Traits\ResourceTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SwiftpayQueryOrderResource extends JsonResource
{
    use ResourceTrait;

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'created_at' => $this->dateReadable($this->created_at),
            'order_status' => $this->order_status,
            'amount' => $this->amount,
            'reference_number' => $this->reference_number,
            'transaction_id' => $this->transaction_id,
        ];
    }
}
