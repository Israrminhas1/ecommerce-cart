<?php

namespace App\Http\Middleware;

use App\Models\Cart;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    protected $rootView = 'app';

    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
            ],
            'isAdmin' => $request->user()?->is_admin ?? false,
            'cart' => fn () => $this->getCartData($request),
            'wishlistCount' => fn () => $this->getWishlistCount($request),
            'flash' => [
                'success' => fn () => $request->session()->get('success'),
                'error' => fn () => $request->session()->get('error'),
            ],
        ];
    }

    protected function getCartData(Request $request): array
    {
        if (!$request->user()) {
            return ['count' => 0, 'total' => 0];
        }

        $cart = Cart::where('user_id', $request->user()->id)
            ->with('items')
            ->first();

        if (!$cart) {
            return ['count' => 0, 'total' => 0];
        }

        return [
            'count' => $cart->items->sum('quantity'),
            'total' => round($cart->items->sum(fn ($item) => $item->quantity * $item->price_at_addition), 2),
        ];
    }

    protected function getWishlistCount(Request $request): int
    {
        if (!$request->user()) {
            return 0;
        }

        return Wishlist::where('user_id', $request->user()->id)->count();
    }
}
