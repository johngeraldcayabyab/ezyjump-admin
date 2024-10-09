<?php

namespace App\Models;

class SwiftpayOrder extends DoModel
{
    protected $table = 'swiftpay_orders';
    protected $connection = 'do_read_mysql';

    public function swiftpayCallback()
    {
        return $this->hasOne(SwiftpayCallback::class, 'reference_id', 'reference_id');
    }

    public function merchant()
    {
        return $this->belongsTo(Merchant::class, 'tenant_id');
    }
}
