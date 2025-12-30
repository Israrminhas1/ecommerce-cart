<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    public function getSubtotalAttribute(): float
    {
        return $this->items->sum(function ($item) {
            return $item->quantity * $item->price_at_addition;
        });
    }

    public function getTaxAttribute(): float
    {
        return round($this->subtotal * 0.10, 2); // 10% tax
    }

    public function getTotalAttribute(): float
    {
        return $this->subtotal + $this->tax;
    }

    public function getItemCountAttribute(): int
    {
        return $this->items->sum('quantity');
    }

    public function isEmpty(): bool
    {
        return $this->items->isEmpty();
    }

    public function hasProduct(Product $product): bool
    {
        return $this->items()->where('product_id', $product->id)->exists();
    }

    public function getItemForProduct(Product $product): ?CartItem
    {
        return $this->items()->where('product_id', $product->id)->first();
    }
}
