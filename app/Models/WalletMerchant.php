<?php

namespace App\Models;

class WalletMerchant extends WalletModel
{
    protected $table = 'merchant';
    protected $connection = 'wallet_write_mysql';
    protected $primaryKey = 'id';
    protected $casts = [
        'id' => 'string',
    ];
}
