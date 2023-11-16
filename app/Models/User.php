<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'channel'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function isAdmin()
    {
        if ($this->type === 'admin') {
            return true;
        }
        return false;
    }

    public function tenants()
    {
        return $this->hasMany(Tenant::class);
    }

    public function getTenantIds()
    {
        $tenants = $this->tenants;
        return $tenants->pluck('tenant_id')->toArray();
    }
}
