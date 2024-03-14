<?php

namespace App\Models;

class SwiftpayQueryOrder extends DoModel
{
    protected $table = 'swiftpay_query_orders';
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
