<?php

namespace App\Models;

class SwiftpayCallback extends DoModel
{
    protected $table = 'swiftpay_callback';
    protected $connection = 'do_read_mysql';
    protected $keyType = 'string';

    public function swiftpayOrder()
    {
        return $this->hasOne(SwiftpayOrder::class, 'reference_id', 'reference_id');
    }
}
