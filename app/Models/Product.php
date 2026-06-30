<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Category;

class Product extends Model
{
    protected $fillable = [

        'category_id',
        'nama_produk',
        'deskripsi',
        'harga',
        'gambar',
        'status',
        'estimasi'

    ];

    /*
    |--------------------------------------------------------------------------
    | RELATION CATEGORY
    |--------------------------------------------------------------------------
    */

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function ratings()
    {
        return $this->hasMany(ProductRating::class);
    }

    public function averageRating()
    {
        return round(
            $this->ratings()->avg('rating') ?? 0,
            1
        );
    }

    public function totalRatings()
    {
        return $this->ratings()->count();
    }
}