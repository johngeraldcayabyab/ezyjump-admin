<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SwiftpayOrder extends Model
{
    use HasFactory;

    protected $table = 'swiftpay_orders';
    protected $connection = 'do_mysql';

    public function swiftpayCallback()
    {
        return $this->hasOne(SwiftpayCallback::class, 'reference_id', 'reference_id');
    }
}
