<?php

namespace App\Http\Resources;

use App\Traits\ResourceTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GcashPaymentResource extends JsonResource
{
    use ResourceTrait;

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'callback_url' => $this->callback_url,
            'created_at' => $this->dateReadable($this->created_at),
            'gcash_reference_number' => $this->gcash_reference_number,
            'preferred_account' => $this->preferred_account,
            'status' => $this->status,
            'tenant_id' => $this->tenant_id,
            'transaction_id' => $this->transaction_id,
            'updated_at' => $this->dateReadable($this->updated_at),
            'version' => $this->version,
            'name' => $this->name,
            'amount' => $this->amount,
        ];
    }
}
