<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CartService
{
    public function getOrCreateCart(User $user): Cart
    {
        return $user->cart ?? Cart::create(['user_id' => $user->id]);
    }

    public function addItem(Cart $cart, Product $product, int $quantity = 1): CartItem
    {
        return DB::transaction(function () use ($cart, $product, $quantity) {
            $existingItem = $cart->items()
                ->where('product_id', $product->id)
                ->first();

            if ($existingItem) {
                $newQuantity = $existingItem->quantity + $quantity;

                if (!$product->hasStock($newQuantity)) {
                    throw new \Exception('Not enough stock available.');
                }

                $existingItem->update(['quantity' => $newQuantity]);
                return $existingItem->fresh();
            }

            if (!$product->hasStock($quantity)) {
                throw new \Exception('Not enough stock available.');
            }

            return $cart->items()->create([
                'product_id' => $product->id,
                'quantity' => $quantity,
                'price_at_addition' => $product->price,
            ]);
        });
    }

    public function updateItemQuantity(CartItem $item, int $quantity): CartItem
    {
        if ($quantity < 1) {
            throw new \InvalidArgumentException('Quantity must be at least 1.');
        }

        if (!$item->product->hasStock($quantity)) {
            throw new \Exception('Not enough stock available.');
        }

        $item->update(['quantity' => $quantity]);
        return $item->fresh();
    }

    public function removeItem(CartItem $item): bool
    {
        return $item->delete();
    }

    public function clearCart(Cart $cart): bool
    {
        return $cart->items()->delete() >= 0;
    }

    public function getCartWithItems(Cart $cart): Cart
    {
        return $cart->load(['items.product.category']);
    }

    public function getCartTotal(Cart $cart): array
    {
        $cart->load('items.product');

        $subtotal = $cart->items->sum(function ($item) {
            return $item->quantity * $item->price_at_addition;
        });

        $tax = round($subtotal * 0.10, 2);
        $total = $subtotal + $tax;

        return [
            'subtotal' => round($subtotal, 2),
            'tax' => $tax,
            'total' => round($total, 2),
            'item_count' => $cart->items->sum('quantity'),
        ];
    }

    public function validateCartStock(Cart $cart): array
    {
        $errors = [];
        $cart->load('items.product');

        foreach ($cart->items as $item) {
            if (!$item->product->is_active) {
                $errors[] = [
                    'item_id' => $item->id,
                    'product_name' => $item->product->name,
                    'message' => 'Product is no longer available.',
                ];
            } elseif (!$item->product->hasStock($item->quantity)) {
                $errors[] = [
                    'item_id' => $item->id,
                    'product_name' => $item->product->name,
                    'message' => "Only {$item->product->stock_quantity} available.",
                    'available_stock' => $item->product->stock_quantity,
                ];
            }
        }

        return $errors;
    }

    public function syncPrices(Cart $cart): void
    {
        $cart->load('items.product');

        foreach ($cart->items as $item) {
            if ($item->price_at_addition != $item->product->price) {
                $item->update(['price_at_addition' => $item->product->price]);
            }
        }
    }
}
