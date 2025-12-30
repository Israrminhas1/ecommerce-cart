<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Services\StockService;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __construct(
        protected StockService $stockService
    ) {}

    public function index(): Response
    {
        $stats = [
            'total_users' => User::count(),
            'total_products' => Product::count(),
            'total_orders' => Order::count(),
            'orders_today' => Order::today()->count(),
            'revenue_today' => Order::today()
                ->whereIn('status', [Order::STATUS_PROCESSING, Order::STATUS_COMPLETED])
                ->sum('total'),
            'pending_orders' => Order::pending()->count(),
        ];

        $stockSummary = $this->stockService->getStockSummary();
        $lowStockProducts = $this->stockService->getLowStockProducts(5);
        $outOfStockProducts = $this->stockService->getOutOfStockProducts(5);

        $recentOrders = Order::with('user')
            ->latest()
            ->limit(5)
            ->get();

        return Inertia::render('Admin/Dashboard', [
            'stats' => $stats,
            'stockSummary' => $stockSummary,
            'lowStockProducts' => $lowStockProducts,
            'outOfStockProducts' => $outOfStockProducts,
            'recentOrders' => $recentOrders,
        ]);
    }
}
