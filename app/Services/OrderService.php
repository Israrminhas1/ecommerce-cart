<?php

namespace App\Services;

use App\Jobs\SendLowStockNotification;
use App\Models\Cart;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function __construct(
        protected CartService $cartService,
        protected StockService $stockService
    ) {}

    public function createFromCart(User $user, ?string $notes = null): Order
    {
        $cart = $this->cartService->getOrCreateCart($user);

        if ($cart->isEmpty()) {
            throw new \Exception('Cart is empty.');
        }

        $stockErrors = $this->cartService->validateCartStock($cart);
        if (!empty($stockErrors)) {
            throw new \Exception('Some items in your cart are no longer available.');
        }

        return DB::transaction(function () use ($user, $cart, $notes) {
            $cart->load('items.product');

            $totals = $this->cartService->getCartTotal($cart);

            $order = Order::create([
                'user_id' => $user->id,
                'order_number' => Order::generateOrderNumber(),
                'status' => Order::STATUS_PENDING,
                'subtotal' => $totals['subtotal'],
                'tax' => $totals['tax'],
                'total' => $totals['total'],
                'notes' => $notes,
            ]);

            foreach ($cart->items as $item) {
                $order->items()->create([
                    'product_id' => $item->product_id,
                    'product_name' => $item->product->name,
                    'quantity' => $item->quantity,
                    'price' => $item->price_at_addition,
                ]);

                $item->product->decrementStock($item->quantity);

                if ($this->stockService->shouldNotifyLowStock($item->product)) {
                    SendLowStockNotification::dispatch($item->product);
                }
            }

            $this->cartService->clearCart($cart);

            $order->markAsProcessing();

            return $order->load('items');
        });
    }

    public function getOrdersForUser(User $user, int $perPage = 10)
    {
        return $user->orders()
            ->with('items')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    public function getOrderDetails(Order $order): Order
    {
        return $order->load(['items.product', 'user']);
    }

    public function updateOrderStatus(Order $order, string $status): bool
    {
        $validStatuses = [
            Order::STATUS_PENDING,
            Order::STATUS_PROCESSING,
            Order::STATUS_COMPLETED,
            Order::STATUS_CANCELLED,
        ];

        if (!in_array($status, $validStatuses)) {
            throw new \InvalidArgumentException('Invalid order status.');
        }

        return $order->update(['status' => $status]);
    }

    public function cancelOrder(Order $order): bool
    {
        if ($order->isCompleted() || $order->isCancelled()) {
            throw new \Exception('Cannot cancel this order.');
        }

        return DB::transaction(function () use ($order) {
            // Restore stock
            foreach ($order->items as $item) {
                if ($item->product) {
                    $item->product->incrementStock($item->quantity);
                }
            }

            return $order->markAsCancelled();
        });
    }

    public function getTodaysSales(): array
    {
        $orders = Order::today()
            ->whereIn('status', [Order::STATUS_PROCESSING, Order::STATUS_COMPLETED])
            ->with('items')
            ->get();

        $totalOrders = $orders->count();
        $totalRevenue = $orders->sum('total');
        $totalItems = $orders->sum(fn ($order) => $order->items->sum('quantity'));

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

        return [
            'date' => now()->toDateString(),
            'total_orders' => $totalOrders,
            'total_revenue' => round($totalRevenue, 2),
            'total_items_sold' => $totalItems,
            'products_sold' => array_values($productsSold),
        ];
    }
}
