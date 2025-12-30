<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'cart_id',
        'product_id',
        'quantity',
        'price_at_addition',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'price_at_addition' => 'decimal:2',
    ];

    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function getTotalAttribute(): float
    {
        return $this->quantity * $this->price_at_addition;
    }

    public function getFormattedTotalAttribute(): string
    {
        return '$' . number_format($this->total, 2);
    }

    public function getFormattedPriceAttribute(): string
    {
        return '$' . number_format($this->price_at_addition, 2);
    }

    public function canIncreaseQuantity(int $amount = 1): bool
    {
        return $this->product && $this->product->hasStock($this->quantity + $amount);
    }

    public function updateQuantity(int $quantity): bool
    {
        if ($quantity < 1) {
            return false;
        }

        if (!$this->product->hasStock($quantity)) {
            return false;
        }

        $this->update(['quantity' => $quantity]);
        return true;
    }
}
