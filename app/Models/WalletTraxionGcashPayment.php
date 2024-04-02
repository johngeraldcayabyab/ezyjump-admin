<?php

namespace App\Models;

class WalletTraxionGcashPayment extends WalletModel
{
    protected $table = 'traxion_gcash_payment';
    protected $connection = 'wallet_read_mysql';
    protected $primaryKey = 'id';
    protected $casts = [
        'id' => 'string',
    ];
}
