<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    protected $table = 'tenants';
    protected $connection = 'mysql';
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
