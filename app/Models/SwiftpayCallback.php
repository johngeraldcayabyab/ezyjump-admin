<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SwiftpayCallback extends DoModel
{
    use HasFactory;

    protected $table = 'swiftpay_callback';
    protected $connection = 'do_read_mysql';
    protected $keyType = 'string';

    public function swiftpayOrder()
    {
        return $this->hasOne(SwiftpayQueryOrder::class, 'reference_id', 'reference_id');
    }

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
