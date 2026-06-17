<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'event_id',
        'order_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'total_price',
        'status',
        'snap_token',
    ];

    // Relasi: 1 Transaksi terikat pada 1 Event
    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
