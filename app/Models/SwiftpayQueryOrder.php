<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class SwiftpayQueryOrder extends DoModel
{
    use HasFactory;

    protected $table = 'swiftpay_query_orders';
    protected $connection = 'do_read_mysql';

    public function swiftpayCallback()
    {
        return $this->hasOne(SwiftpayCallback::class, 'reference_id', 'reference_id');
    }
}
