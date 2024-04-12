<?php

namespace App\Models;

class WalletArxPayment extends WalletModel
{
    protected $table = 'arx_payment';
    protected $connection = 'wallet_read_mysql';
    protected $primaryKey = 'id';
}
