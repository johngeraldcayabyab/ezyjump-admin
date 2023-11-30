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
            'created_at' => $this->dateReadable($this->created_at),
            'transaction_id' => $this->transaction_id,
            'payment_id' => $this->payment_id,
            'payment_status' => $this->payment_status,
            'updated_at' => $this->dateReadable($this->updated_at),
            'amount' => $this->amount,
        ];
    }
}
