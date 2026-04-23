<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        OrderDetail::truncate();
        Order::truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        for ($i = 1; $i <= 10; $i++) {

            $order = Order::create([
                'kode' => 'M' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'nama_customer' => ['Caca','Arul','Budi','Sinta','Dewi','Rizky','Putri','Andi','Fajar','Lina'][$i-1],
                'no_hp' => '0812345678' . $i,
                'alamat' => 'Alamat ke-' . $i,
                'tanggal_pesan' => now()->subDays(10 - $i),
                'tanggal_kirim' => now()->subDays(8 - $i),
                'status' => $i % 2 == 0 ? 'selesai' : 'diproses'
            ]);

            $jumlahProduk = rand(1, 5);

            $products = Product::where('status', 'active')
                ->inRandomOrder()
                ->take($jumlahProduk)
                ->get();

            if ($products->isEmpty()) {
                continue;
            }

            foreach ($products as $product) {
                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'qty' => rand(1, 5),
                    'harga' => $product->harga
                ]);
            }
        }
    }
}