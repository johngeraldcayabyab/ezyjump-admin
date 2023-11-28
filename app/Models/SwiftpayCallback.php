<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class SwiftpayCallback extends DoModel
{
    use HasFactory;

    protected $table = 'swiftpay_callback';
    protected $connection = 'do_read_mysql';
    protected $keyType = 'string';

    public function swiftpayOrder()
    {
        return $this->hasOne(SwiftpayQueryOrder::class, 'reference_id', 'reference_id');
    }
}
