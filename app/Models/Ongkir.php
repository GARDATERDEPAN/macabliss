<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ongkir extends Model
{
    protected $table = 'ongkir';

    protected $fillable = [
        'jarak_min',
        'jarak_max',
        'tarif'
    ];
}
