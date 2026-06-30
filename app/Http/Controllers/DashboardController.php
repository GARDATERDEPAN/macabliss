<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Payment;
use App\Models\User;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        /*
        |--------------------------------------------------------------------------
        | RINGKASAN
        |--------------------------------------------------------------------------
        */

        $totalProduk = Product::count();

        $totalPesanan = Order::count();

        $totalCustomer = User::where(
            'role',
            'customer'
        )->count();

        $totalPendapatan = Payment::where(
            'status',
            'paid'
        )->sum('jumlah');


        /*
        |--------------------------------------------------------------------------
        | PESANAN TERBARU
        |--------------------------------------------------------------------------
        */

        $latestOrders = Order::with([
                'user',
                'details.product'
            ])
            ->latest()
            ->take(3)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | PEMBAYARAN TERBARU
        |--------------------------------------------------------------------------
        */

        $latestPayments = Payment::with([
                'order.user'
            ])
            ->latest()
            ->take(3)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | BEST SELLER
        |--------------------------------------------------------------------------
        */

        $bestProducts = OrderDetail::select(
                'product_id',
                DB::raw('SUM(qty) as total_terjual')
            )
            ->with('product')
            ->groupBy('product_id')
            ->orderByDesc('total_terjual')
            ->take(4)
            ->get()
            ->map(function ($item) {

                return (object) [

                    'nama_produk' => $item->product->nama_produk ?? '-',

                    'total_terjual' => $item->total_terjual

                ];
            });


        /*
        |--------------------------------------------------------------------------
        | DATA BULAN
        |--------------------------------------------------------------------------
        */

        $months = [

            'Jan',
            'Feb',
            'Mar',
            'Apr',
            'Mei',
            'Jun',
            'Jul',
            'Agu',
            'Sep',
            'Okt',
            'Nov',
            'Des'

        ];


        /*
        |--------------------------------------------------------------------------
        | CHART PENJUALAN
        |--------------------------------------------------------------------------
        */

        $monthlySales = [];

        for ($i = 1; $i <= 12; $i++) {

            $monthlySales[] = Payment::where(
                    'status',
                    'paid'
                )
                ->whereMonth(
                    'created_at',
                    $i
                )
                ->whereYear(
                    'created_at',
                    now()->year
                )
                ->sum('jumlah');
        }


        /*
        |--------------------------------------------------------------------------
        | RETURN VIEW
        |--------------------------------------------------------------------------
        */

        return view(
            'dashboard',
            compact(

                'totalProduk',
                'totalPesanan',
                'totalCustomer',
                'totalPendapatan',

                'latestOrders',
                'latestPayments',

                'bestProducts',

                'months',
                'monthlySales'
            )
        );
    }
}