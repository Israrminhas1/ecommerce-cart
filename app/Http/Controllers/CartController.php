<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class CartController extends Controller
{
    public function __construct(
        protected CartService $cartService
    ) {}

    public function index(): Response
    {
        $cart = $this->cartService->getOrCreateCart(auth()->user());
        $cart = $this->cartService->getCartWithItems($cart);
        $totals = $this->cartService->getCartTotal($cart);
        $stockErrors = $this->cartService->validateCartStock($cart);

        return Inertia::render('Cart/Index', [
            'cart' => $cart,
            'items' => $cart->items,
            'totals' => $totals,
            'stockErrors' => $stockErrors,
        ]);
    }

    public function clear(): RedirectResponse
    {
        $cart = $this->cartService->getOrCreateCart(auth()->user());
        $this->cartService->clearCart($cart);

        return redirect()->route('cart.index')
            ->with('success', 'Cart cleared successfully.');
    }
}
