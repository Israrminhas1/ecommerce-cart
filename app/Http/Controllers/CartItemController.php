<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddToCartRequest;
use App\Http\Requests\UpdateCartItemRequest;
use App\Models\CartItem;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\RedirectResponse;

class CartItemController extends Controller
{
    public function __construct(
        protected CartService $cartService
    ) {}

    public function store(AddToCartRequest $request): RedirectResponse
    {
        $product = Product::findOrFail($request->product_id);

        if (!$product->is_active) {
            return back()->with('error', 'This product is no longer available.');
        }

        $cart = $this->cartService->getOrCreateCart(auth()->user());

        try {
            $this->cartService->addItem($cart, $product, $request->quantity ?? 1);
            return back()->with('success', "{$product->name} added to cart!");
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function update(UpdateCartItemRequest $request, CartItem $cartItem): RedirectResponse
    {
        // Ensure the cart item belongs to the authenticated user
        if ($cartItem->cart->user_id !== auth()->id()) {
            abort(403);
        }

        try {
            $this->cartService->updateItemQuantity($cartItem, $request->quantity);
            return back()->with('success', 'Cart updated successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy(CartItem $cartItem): RedirectResponse
    {
        // Ensure the cart item belongs to the authenticated user
        if ($cartItem->cart->user_id !== auth()->id()) {
            abort(403);
        }

        $productName = $cartItem->product->name ?? 'Item';
        $this->cartService->removeItem($cartItem);

        return back()->with('success', "{$productName} removed from cart.");
    }
}
