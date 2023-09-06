<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SwiftpayCallback extends Model
{
    use HasFactory;

    protected $table = 'swiftpay_callback';
    protected $connection = 'do_mysql';

    public function swiftpayOrder()
    {
        return $this->hasOne(SwiftpayOrder::class, 'tenant_id', 'tenant_id');
    }
}
