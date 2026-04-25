<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'kode',
        'nama_customer',
        'no_hp',
        'alamat',
        'tanggal_pesan',
        'tanggal_kirim',
        'metode_pembayaran',
        'metode_pengambilan', // 🔥 kalau kamu pakai ini
        'ongkir',
        'total_harga',
        'status',
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