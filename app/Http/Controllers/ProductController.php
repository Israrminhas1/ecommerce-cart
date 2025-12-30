<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    public function index(Request $request): Response
    {
        $query = Product::query()
            ->with('category')
            ->active();

        // Category filter
        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Search filter
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Stock filter
        if ($request->filled('stock')) {
            match ($request->stock) {
                'in_stock' => $query->inStock(),
                'low_stock' => $query->lowStock(),
                'out_of_stock' => $query->outOfStock(),
                default => null,
            };
        }

        // Sorting
        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');

        $allowedSorts = ['name', 'price', 'created_at', 'stock_quantity'];
        if (in_array($sortField, $allowedSorts)) {
            $query->orderBy($sortField, $sortDirection === 'asc' ? 'asc' : 'desc');
        }

        $products = $query->paginate(12)->withQueryString();

        $categories = Category::active()->orderBy('name')->get();

        // Get user's wishlist product IDs
        $wishlistIds = [];
        if (auth()->check()) {
            $wishlistIds = Wishlist::where('user_id', auth()->id())
                ->pluck('product_id')
                ->toArray();
        }

        return Inertia::render('Products/Index', [
            'products' => $products,
            'categories' => $categories,
            'filters' => [
                'category' => $request->get('category'),
                'search' => $request->get('search'),
                'stock' => $request->get('stock'),
                'sort' => $sortField,
                'direction' => $sortDirection,
            ],
            'wishlistIds' => $wishlistIds,
        ]);
    }

    public function show(Product $product): Response
    {
        if (!$product->is_active) {
            abort(404);
        }

        $product->load('category');

        $relatedProducts = Product::active()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->inStock()
            ->limit(4)
            ->get();

        $isInWishlist = false;
        if (auth()->check()) {
            $isInWishlist = Wishlist::hasProduct(auth()->user(), $product);
        }

        return Inertia::render('Products/Show', [
            'product' => $product,
            'relatedProducts' => $relatedProducts,
            'isInWishlist' => $isInWishlist,
        ]);
    }
}
