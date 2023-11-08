<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GcashPayment extends Model
{
    use HasFactory;

    protected $table = 'gcash_payments';
    protected $connection = 'do_read_mysql';

    public function scopeCreatedAtBetween($query, $from, $to)
    {
        $dateFrom = Carbon::today()->startOfDay()->subHours(8);
        $dateTo = now();
        if (Carbon::hasFormat($from, 'Y-m-d') && Carbon::hasFormat($to, 'Y-m-d')) {
            $dateFrom = Carbon::parse($from)->startOfDay()->timezone('UTC')->subHours(8);
            $dateTo = Carbon::parse($to)->endOfDay()->timezone('UTC')->subHours(8);
        }
        return $query->whereBetween('created_at', [$dateFrom, $dateTo]);
    }
}
