<?php

namespace App\Models;

class WalletMerchantKey extends WalletModel
{
    protected $table = 'merchant_key';
    protected $connection = 'wallet_read_mysql';
    protected $primaryKey = 'id';

    public function merchant()
    {

    }
}
