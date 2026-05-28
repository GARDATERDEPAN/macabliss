<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Payment;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProduk = Product::count();

        $totalPesanan = Order::count();

        $paymentLunas = Payment::where('status', 'paid')->count();

        $paymentPending = Payment::where('status', 'pending')->count();

        $totalPendapatan = Payment::where('status', 'paid')
                            ->sum('jumlah');

        // PESANAN DIPROSES
        $pesananDiproses = Order::where('status', 'diproses')->count();

        // PESANAN SELESAI
        $pesananSelesai = Order::where('status', 'selesai')->count();

        // PESANAN TERBARU
        $latestOrders = Order::latest()->take(5)->get();

        // PAYMENT TERBARU
        $latestPayments = Payment::latest()->take(5)->get();

        return view('dashboard', compact(
            'totalProduk',
            'totalPesanan',
            'paymentLunas',
            'paymentPending',
            'totalPendapatan',
            'pesananDiproses',
            'pesananSelesai',
            'latestOrders',
            'latestPayments'
        ));
    }
}