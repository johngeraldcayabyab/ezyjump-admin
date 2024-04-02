<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class WalletMerchant extends Authenticatable
{
    protected $table = 'merchant';
    protected $connection = 'wallet_read_mysql';
    protected $primaryKey = 'id';
    protected $casts = [
        'id' => 'string',
    ];

    public function merchantKey()
    {
        return $this->hasOne(WalletMerchantKey::class, 'merchant_id');
    }
}
