<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SwiftpayQueryOrder extends Model
{
    use HasFactory;

    protected $table = 'swiftpay_query_orders';
    protected $connection = 'do_mysql';

    public function swiftpayCallback()
    {
        return $this->hasOne(SwiftpayCallback::class, 'reference_id', 'reference_id');
    }

    public function scopeCreatedAtBetween($query, $from, $to)
    {
        $dateFrom = Carbon::yesterday()->startOfDay()->subHours(8);
        $dateTo = now();
        if (Carbon::hasFormat($from, 'Y-m-d') && Carbon::hasFormat($to, 'Y-m-d')) {
            $dateFrom = Carbon::parse($from)->startOfDay()->timezone('UTC')->subHours(8);
            $dateTo = Carbon::parse($to)->endOfDay()->timezone('UTC')->subHours(8);
        }
        return $query->whereBetween('created_at', [$dateFrom, $dateTo]);
    }
}
