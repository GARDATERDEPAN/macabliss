<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public function details()
    {
        return $this->hasMany(OrderDetail::class);
        
    }

    protected $fillable = [
    'kode',
    'nama_customer',
    'no_hp',
    'alamat',
    'tanggal_pesan',
    'tanggal_kirim',
    'metode_pembayaran',
    'ongkir',
    'total_harga',
    'status',
];
}
