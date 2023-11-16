<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class GcashPayment extends DoModel
{
    use HasFactory;

    protected $table = 'gcash_payments';
    protected $connection = 'do_read_mysql';
}
