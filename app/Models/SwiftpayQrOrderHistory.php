<?php

namespace App\Models;

class SwiftpayQrOrderHistory extends DoModel
{
    protected $table = 'swiftpay_qr_order_history';
    protected $connection = 'do_read_mysql';
    protected $keyType = 'string';
}
