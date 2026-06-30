<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    protected $fillable = [

        'customer_id',
        'admin_id',
        'message',
        'image',
        'sender',
        'is_read'

    ];

    public function customer()
    {
        return $this->belongsTo(
            User::class,
            'customer_id'
        );
    }

    public function admin()
    {
        return $this->belongsTo(
            User::class,
            'admin_id'
        );
    }
}