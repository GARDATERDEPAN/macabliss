<?php

namespace App\Http\Controllers;

use App\Models\ProductRating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductRatingController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([

            'order_id'   => 'required|exists:orders,id',

            'product_id' => 'required|exists:products,id',

            'rating'     => 'required|integer|min:1|max:5',

            'komentar'   => 'nullable|string|max:500',

        ]);


        $userId = Auth::guard('customer')->id();


        // Cek apakah customer sudah pernah memberi rating
        $sudahAda = ProductRating::where(
                'user_id',
                $userId
            )
            ->where(
                'order_id',
                $request->order_id
            )
            ->where(
                'product_id',
                $request->product_id
            )
            ->exists();


        if ($sudahAda) {

            return back()->with(
                'error',
                'Anda sudah memberikan ulasan untuk produk ini.'
            );

        }


        ProductRating::create([

            'user_id'    => $userId,

            'order_id'   => $request->order_id,

            'product_id' => $request->product_id,

            'rating'     => $request->rating,

            'komentar'   => $request->komentar,

        ]);


        return back()->with(
            'success',
            'Rating berhasil diberikan.'
        );
    }
}