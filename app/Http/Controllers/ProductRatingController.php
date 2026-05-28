<?php

namespace App\Http\Controllers;

use App\Models\ProductRating;
use Illuminate\Http\Request;

class ProductRatingController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        ProductRating::create([
            'product_id' => $request->product_id,
            'rating' => $request->rating,
        ]);

        return back()->with('success', 'Rating berhasil diberikan');
    }
}