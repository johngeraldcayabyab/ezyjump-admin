<?php

namespace App\Http\Resources;

use App\Traits\ResourceTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WalletArxPaymentResource extends JsonResource
{
    use ResourceTrait;

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'created_at' => $this->dateReadable($this->created_at),
            'order_id' => $this->order_id,
            'transaction_id' => $this->transaction_id,
            'amount' => $this->amount,
            'arx_status' => $this->arx_status,
        ];
    }
}
