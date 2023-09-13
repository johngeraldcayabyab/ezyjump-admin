<?php

namespace App\Http\Resources;

use App\Traits\ResourceTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SwiftpayOrderResource extends JsonResource
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
            'address1' => $this->address1,
            'address2' => $this->address2,
            'city' => $this->city,
            'country' => $this->country,
            'customer_name' => $this->customer_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'postcode' => $this->postcode,
            'state' => $this->state,
            'generate_customer_redirect_url' => $this->generate_customer_redirect_url,
            'generate_customer_redirect_url_flag' => $this->generate_customer_redirect_url_flag,
            'institution_code' => $this->institution_code,
            'order_status' => $this->order_status,
            'amount' => $this->amount,
            'net_amount' => $this->net_amount,
            'transaction_fee' => $this->transaction_fee,
            'vat' => $this->vat,
            'payment_id' => $this->payment_id,
            'reference_number' => $this->reference_number,
            'signature' => $this->signature,
            'transaction_id' => $this->transaction_id,
        ];
    }
}
