<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class SwiftpayQrOrderHistory extends DoModel
{
    use HasFactory;

    protected $table = 'swiftpay_qr_order_history';
    protected $connection = 'do_read_mysql';
    protected $keyType = 'string';
}
