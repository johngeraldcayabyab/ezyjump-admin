<?php

namespace App\Models;

use App\Traits\ModelTrait;
use Illuminate\Database\Eloquent\Model;


class WalletModel extends Model
{
    use ModelTrait;

    public function scopeMerchantId($query, $value)
    {
        if (is_array($value)) {
            return $query->whereIn('merchant_id', $value);
        }
        return $query->where('merchant_id', $value);
    }
}
