<?php

namespace App\Models;

class WalletTraxionGcashPayment extends WalletModel
{
    protected $table = 'traxion_gcash_payment';
    protected $connection = 'wallet_read_mysql';
    protected $primaryKey = 'id';

    public function output()
    {
        return $this->hasOne(WalletTraxionTransactionOutput::class, 'gcash_id', 'id');
    }
}
