<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Wishlist;
use App\Services\CartService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class WishlistController extends Controller
{
    public function __construct(
        protected CartService $cartService
    ) {}

    public function index(): Response
    {
        $wishlistItems = Wishlist::where('user_id', auth()->id())
            ->with(['product.category'])
            ->latest()
            ->paginate(12);

        return Inertia::render('Wishlist/Index', [
            'items' => $wishlistItems,
        ]);
    }

    public function toggle(Product $product): RedirectResponse
    {
        $added = Wishlist::toggle(auth()->user(), $product);

        $message = $added
            ? "{$product->name} added to wishlist!"
            : "{$product->name} removed from wishlist.";

        return back()->with('success', $message);
    }

    public function moveToCart(Product $product): RedirectResponse
    {
        $wishlist = Wishlist::where('user_id', auth()->id())
            ->where('product_id', $product->id)
            ->first();

        if (!$wishlist) {
            return back()->with('error', 'Item not found in wishlist.');
        }

        if (!$product->is_active) {
            return back()->with('error', 'This product is no longer available.');
        }

        if (!$product->isInStock()) {
            return back()->with('error', 'This product is out of stock.');
        }

        $cart = $this->cartService->getOrCreateCart(auth()->user());

        try {
            $this->cartService->addItem($cart, $product, 1);
            $wishlist->delete();

            return back()->with('success', "{$product->name} moved to cart!");
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
