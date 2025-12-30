<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Wishlist extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public static function toggle(User $user, Product $product): bool
    {
        $wishlist = static::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->first();

        if ($wishlist) {
            $wishlist->delete();
            return false; // Removed from wishlist
        }

        static::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);

        return true; // Added to wishlist
    }

    public static function hasProduct(User $user, Product $product): bool
    {
        return static::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->exists();
    }
}
