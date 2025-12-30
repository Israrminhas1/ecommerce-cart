<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\SendDailySalesReport;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ReportController extends Controller
{
    public function __construct(
        protected OrderService $orderService
    ) {}

    public function index(Request $request): Response
    {
        $startDate = $request->get('start_date', now()->subDays(30)->toDateString());
        $endDate = $request->get('end_date', now()->toDateString());

        $orders = Order::whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->whereIn('status', [Order::STATUS_PROCESSING, Order::STATUS_COMPLETED])
            ->with('items')
            ->get();

        $totalRevenue = $orders->sum('total');
        $totalOrders = $orders->count();
        $totalItems = $orders->sum(fn ($order) => $order->items->sum('quantity'));
        $averageOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;

        // Daily breakdown
        $dailyData = $orders->groupBy(fn ($order) => $order->created_at->toDateString())
            ->map(fn ($dayOrders) => [
                'orders' => $dayOrders->count(),
                'revenue' => $dayOrders->sum('total'),
            ]);

        // Top products
        $productsSold = [];
        foreach ($orders as $order) {
            foreach ($order->items as $item) {
                $key = $item->product_id ?? $item->product_name;
                if (!isset($productsSold[$key])) {
                    $productsSold[$key] = [
                        'name' => $item->product_name,
                        'quantity' => 0,
                        'revenue' => 0,
                    ];
                }
                $productsSold[$key]['quantity'] += $item->quantity;
                $productsSold[$key]['revenue'] += $item->total;
            }
        }

        usort($productsSold, fn ($a, $b) => $b['revenue'] - $a['revenue']);
        $topProducts = array_slice($productsSold, 0, 10);

        $todaysSales = $this->orderService->getTodaysSales();

        return Inertia::render('Admin/Reports/Index', [
            'summary' => [
                'total_revenue' => round($totalRevenue, 2),
                'total_orders' => $totalOrders,
                'total_items' => $totalItems,
                'average_order_value' => round($averageOrderValue, 2),
            ],
            'dailyData' => $dailyData,
            'topProducts' => $topProducts,
            'todaysSales' => $todaysSales,
            'filters' => [
                'start_date' => $startDate,
                'end_date' => $endDate,
            ],
        ]);
    }

    public function generate(): RedirectResponse
    {
        SendDailySalesReport::dispatch();

        return back()->with('success', 'Sales report is being generated and will be sent to admin.');
    }
}
