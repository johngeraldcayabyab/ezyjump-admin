<?php

namespace App\Models;

class WalletMagpieDeposit extends WalletModel
{
    protected $table = 'magpie_deposit';
    protected $connection = 'wallet_read_mysql';
    protected $primaryKey = 'id';
}
