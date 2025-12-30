<?php

namespace App\Services;

use App\Models\LowStockNotification;
use App\Models\Product;

class StockService
{
    public function checkLowStock(Product $product): bool
    {
        return $product->isLowStock();
    }

    public function shouldNotifyLowStock(Product $product): bool
    {
        if (!$this->checkLowStock($product)) {
            return false;
        }

        return !LowStockNotification::wasRecentlyNotified($product);
    }

    public function recordLowStockNotification(Product $product): LowStockNotification
    {
        return LowStockNotification::recordNotification($product);
    }

    public function getLowStockProducts(int $limit = 10)
    {
        return Product::active()
            ->lowStock()
            ->with('category')
            ->orderBy('stock_quantity', 'asc')
            ->limit($limit)
            ->get();
    }

    public function getOutOfStockProducts(int $limit = 10)
    {
        return Product::active()
            ->outOfStock()
            ->with('category')
            ->limit($limit)
            ->get();
    }

    public function getStockSummary(): array
    {
        return [
            'total_products' => Product::active()->count(),
            'in_stock' => Product::active()->inStock()->whereColumn('stock_quantity', '>', 'low_stock_threshold')->count(),
            'low_stock' => Product::active()->lowStock()->count(),
            'out_of_stock' => Product::active()->outOfStock()->count(),
        ];
    }
}
