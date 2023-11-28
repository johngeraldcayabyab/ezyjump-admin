<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class PayboritPaymentHistory extends DoModel
{
    use HasFactory;

    protected $table = 'payborit_payment_history';
    protected $connection = 'do_read_mysql';
}
