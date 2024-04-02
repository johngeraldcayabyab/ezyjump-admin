<?php

namespace App\Models;

use App\Traits\ModelTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class DoModel extends Model
{
    use ModelTrait;

    public function scopeTenantId($query, $value)
    {
        if (is_array($value)) {
            return $query->whereIn('tenant_id', $value);
        }
        return $query->where('tenant_id', $value);
    }
}
