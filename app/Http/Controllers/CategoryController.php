<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CategoryController extends Controller
{
    public function index(): Response
    {
        $categories = Category::active()
            ->withCount(['products' => function ($query) {
                $query->active();
            }])
            ->orderBy('name')
            ->get();

        return Inertia::render('Categories/Index', [
            'categories' => $categories,
        ]);
    }

    public function show(Request $request, Category $category): Response
    {
        if (!$category->is_active) {
            abort(404);
        }

        $sort = $request->get('sort', 'created_at');
        $direction = $request->get('direction', 'desc');

        $validSorts = ['created_at', 'price', 'name'];
        $sort = in_array($sort, $validSorts) ? $sort : 'created_at';
        $direction = in_array($direction, ['asc', 'desc']) ? $direction : 'desc';

        $products = $category->products()
            ->active()
            ->orderBy($sort, $direction)
            ->paginate(12)
            ->withQueryString();

        $wishlistIds = [];
        if (auth()->check()) {
            $wishlistIds = Wishlist::where('user_id', auth()->id())
                ->pluck('product_id')
                ->toArray();
        }

        return Inertia::render('Categories/Show', [
            'category' => $category,
            'products' => $products,
            'wishlistIds' => $wishlistIds,
            'filters' => [
                'sort' => $sort,
                'direction' => $direction,
            ],
        ]);
    }
}
