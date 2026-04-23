<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with('order');

        // SEARCH (kode pembayaran / kode order)
        if ($request->search) {
            $query->where('kode_pembayaran', 'like', '%' . $request->search . '%')
                  ->orWhereHas('order', function ($q) use ($request) {
                      $q->where('kode', 'like', '%' . $request->search . '%');
                  });
        }

        // FILTER STATUS
        if ($request->status) {
            $query->where('status', $request->status);
        }

        $payments = $query->latest()->paginate(5)->withQueryString();

        return view('payments.index', compact('payments'));
    }

    public function show($id)
    {
        $payment = Payment::with('order')->findOrFail($id);
        return view('payments.show', compact('payment'));
    }

    public function update(Request $request, $id)
    {
        $payment = Payment::findOrFail($id);

        if ($payment->status == 'lunas') {
            return back();
        }

        $request->validate([
            'status' => 'required|in:pending,lunas'
        ]);

        $payment->update([
            'status' => $request->status
        ]);

        return back();
    }
}