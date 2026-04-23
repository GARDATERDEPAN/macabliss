<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard', [
            'totalProduk' => \App\Models\Product::count(),
            'totalOrder' => \App\Models\Order::count(),
            'totalPayment' => \App\Models\Payment::count(),
            'totalPendapatan' => \App\Models\Payment::where('status', 'lunas')->sum('jumlah'),

            'orderDiproses' => \App\Models\Order::where('status', 'diproses')->count(),
            'orderSelesai' => \App\Models\Order::where('status', 'selesai')->count(),

            'latestOrders' => \App\Models\Order::latest()->take(5)->get(),
            'latestPayments' => \App\Models\Payment::latest()->take(5)->get(),
        ]);
    }
}
