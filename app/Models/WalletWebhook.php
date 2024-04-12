<?php

namespace App\Models;

class WalletWebhook extends WalletModel
{
    protected $table = 'webhook';
    protected $connection = 'wallet_read_mysql';
    protected $primaryKey = 'id';
    protected $casts = [
        'id' => 'string',
    ];
}
