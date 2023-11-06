<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GcashSms extends Model
{
    use HasFactory;

    protected $table = 'gcash_sms';
    protected $connection = 'do_read_mysql';
}
