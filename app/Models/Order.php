<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'session_id',
        'kode',
        'nama_customer',
        'no_hp',
        'alamat',
        'tanggal_pesan',
        'tanggal_kirim',
        'metode_pembayaran',
        'metode_pengambilan',
        'ongkir',
        'total_harga',
        'status',
        'snap_token',
        'payment_status',
        'expired_at',
    ];

    public function details()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}