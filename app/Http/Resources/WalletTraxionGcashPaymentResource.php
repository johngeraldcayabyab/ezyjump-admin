<?php

namespace App\Http\Resources;

use App\Traits\ResourceTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WalletTraxionGcashPaymentResource extends JsonResource
{
    use ResourceTrait;

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'created_at' => $this->dateReadable($this->created_at),
            'reference_number' => $this->reference_number,
            'transaction_id' => $this->transaction_id,
        ];
    }
}
