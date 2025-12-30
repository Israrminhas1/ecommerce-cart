<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LowStockNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'notified_at',
        'stock_level',
    ];

    protected $casts = [
        'notified_at' => 'datetime',
        'stock_level' => 'integer',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public static function wasRecentlyNotified(Product $product, int $hours = 24): bool
    {
        return static::where('product_id', $product->id)
            ->where('notified_at', '>=', now()->subHours($hours))
            ->exists();
    }

    public static function recordNotification(Product $product): self
    {
        return static::create([
            'product_id' => $product->id,
            'notified_at' => now(),
            'stock_level' => $product->stock_quantity,
        ]);
    }
}
