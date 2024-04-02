<?php

namespace App\Http\Resources;

use App\Traits\ResourceTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WalletTraxionTransactionOutputResource extends JsonResource
{
    use ResourceTrait;

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'thirdparty_reference_number' => $this->thirdparty_reference_number,
            'amount' => $this->amount,
            'transaction_status' => $this->transaction_status,
        ];
    }
}
