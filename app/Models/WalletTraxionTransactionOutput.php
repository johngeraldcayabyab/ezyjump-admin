<?php

namespace App\Models;

class WalletTraxionTransactionOutput extends WalletModel
{
    protected $table = 'traxion_transaction_output';
    protected $connection = 'wallet_read_mysql';
    protected $primaryKey = 'id';

    public function payment()
    {
        return $this->belongsTo(WalletTraxionGcashPayment::class, 'reference_number', 'thirdparty_reference_number');
    }
}
