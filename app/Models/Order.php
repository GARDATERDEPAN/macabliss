<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
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

    // 🔥 RELASI KE DETAIL PRODUK
    public function details()
    {
        return $this->hasMany(OrderDetail::class);
    }

    // 🔥 RELASI KE PAYMENT (PENTING)
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}