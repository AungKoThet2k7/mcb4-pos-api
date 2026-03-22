<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    /** @use HasFactory<\Database\Factories\VoucherFactory> */
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'customer_id',
        'date',
        'total',
        'tax',
        'net_total',
        'cash',
        'change',
        'voucher_items_count',
        'user_id',
        'type',
    ];

    protected $with = [
        'user',
        'voucherItems',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function voucherItems()
    {
        return $this->hasMany(VoucherItem::class);
    }

    // Search Query Scope
    public function scopeSearch($q, $keyword)
    {
        $q->when($keyword, function ($q) use ($keyword) {
            $q->where(function ($q) use ($keyword) {
                $q->where('invoice_number', 'like', '%'.$keyword.'%');
            });
        });
    }

    // Date Filter Scope
    public function scopeDateFilter($q, $start_date, $end_date)
    {
        $q->when($start_date && $end_date, function ($q) use ($start_date, $end_date) {
            $q->whereBetween('date', [Carbon::parse($start_date)->startOfDay(), Carbon::parse($end_date)->endOfDay()]);
        })
            ->when($start_date && ! $end_date, function ($q) use ($start_date) {
                $q->whereDate('date', '>=', Carbon::parse($start_date)->startOfDay());
            })
            ->when(! $start_date && $end_date, function ($q) use ($end_date) {
                $q->whereDate('date', '<=', Carbon::parse($end_date)->endOfDay());
            });
    }
}
