<?php

namespace App\Models\Wallet;

use App\Models\DoModel;

class WalletMerchant extends DoModel
{
    protected $table = 'merchant';
    protected $connection = 'wallet_read';
    protected $primaryKey = 'id';
    protected $casts = [
        'id' => 'string',
    ];
}
