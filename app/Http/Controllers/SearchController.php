<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SearchController extends Controller
{
    public function index(Request $request): Response
    {
        $query = $request->get('q', '');

        $products = collect();

        if (strlen($query) >= 2) {
            $products = Product::active()
                ->search($query)
                ->with('category')
                ->paginate(12)
                ->withQueryString();
        }

        $wishlistIds = [];
        if (auth()->check()) {
            $wishlistIds = Wishlist::where('user_id', auth()->id())
                ->pluck('product_id')
                ->toArray();
        }

        return Inertia::render('Search/Index', [
            'products' => $products,
            'query' => $query,
            'wishlistIds' => $wishlistIds,
        ]);
    }

    public function suggestions(Request $request): JsonResponse
    {
        $query = $request->get('q', '');

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $products = Product::active()
            ->search($query)
            ->select('id', 'name', 'slug', 'price', 'image')
            ->limit(5)
            ->get();

        return response()->json($products);
    }
}
