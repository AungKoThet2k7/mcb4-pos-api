<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    /** @use HasFactory<\Database\Factories\VoucherFactory> */
    use HasFactory;

    protected $fillable = [
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

    // public function customer()
    // {
    //     return $this->belongsTo(Customer::class);
    // }

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
                $q->where('id', 'like', '%'.$keyword.'%');
                    // ->orWhereHas('customer', function ($q) use ($keyword) {
                    //     $q->where('name', 'like', '%'.$keyword.'%')
                    //         ->orWhere('phone', 'like', '%'.$keyword.'%');
                    // });
            });
        });
    }
}
