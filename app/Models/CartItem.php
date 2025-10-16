<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'session_id',
        'product_id',
        'quantity',
        'price',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'price' => 'decimal:2',
    ];

    /**
     * Get the user that owns the cart item.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the product for this cart item.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the subtotal for this cart item.
     */
    public function getSubtotalAttribute()
    {
        return $this->quantity * $this->price;
    }
}
