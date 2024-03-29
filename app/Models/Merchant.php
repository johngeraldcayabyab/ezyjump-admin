<?php

namespace App\Models;

class Merchant extends DoModel
{
    protected $table = 'merchants';
    protected $connection = 'do_write_mysql';
    protected $primaryKey = 'id';
    protected $casts = [
        'id' => 'string',
    ];

    public function tenant()
    {
        return $this->hasOne(Tenant::class, 'tenant_id');
    }
}
