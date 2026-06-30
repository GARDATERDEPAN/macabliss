<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Order;
use App\Models\ProductRating;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable([
    'name',
    'email',
    'phone',
    'password',
    'role'
])]
#[Hidden([
    'password',
    'remember_token'
])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /*
    |--------------------------------------------------------------------------
    | RELATION ORDERS
    |--------------------------------------------------------------------------
    */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /*
    |--------------------------------------------------------------------------
    | RELATION PRODUCT RATINGS
    |--------------------------------------------------------------------------
    */
    public function ratings()
    {
        return $this->hasMany(ProductRating::class);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}