<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'kode_pembayaran',
        'order_id',
        'metode',
        'payment_ref',
        'tanggal_bayar',
        'jumlah',
        'status'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
