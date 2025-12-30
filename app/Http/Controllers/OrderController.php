<?php

namespace App\Http\Controllers;

use App\Http\Requests\PlaceOrderRequest;
use App\Models\Order;
use App\Services\CartService;
use App\Services\OrderService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class OrderController extends Controller
{
    public function __construct(
        protected OrderService $orderService,
        protected CartService $cartService
    ) {}

    public function index(): Response
    {
        $orders = $this->orderService->getOrdersForUser(auth()->user());

        return Inertia::render('Orders/Index', [
            'orders' => $orders,
        ]);
    }

    public function show(Order $order): Response
    {
        // Ensure the order belongs to the authenticated user (unless admin)
        if ($order->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403);
        }

        $order = $this->orderService->getOrderDetails($order);

        return Inertia::render('Orders/Show', [
            'order' => $order,
        ]);
    }

    public function checkout(): Response
    {
        $cart = $this->cartService->getOrCreateCart(auth()->user());
        $cart = $this->cartService->getCartWithItems($cart);
        $totals = $this->cartService->getCartTotal($cart);
        $stockErrors = $this->cartService->validateCartStock($cart);

        if ($cart->isEmpty()) {
            return Inertia::render('Cart/Index', [
                'cart' => $cart,
                'items' => $cart->items,
                'totals' => $totals,
                'stockErrors' => [],
            ]);
        }

        return Inertia::render('Checkout/Index', [
            'cart' => $cart,
            'items' => $cart->items,
            'totals' => $totals,
            'stockErrors' => $stockErrors,
        ]);
    }

    public function store(PlaceOrderRequest $request): RedirectResponse
    {
        try {
            $order = $this->orderService->createFromCart(
                auth()->user(),
                $request->notes
            );

            return redirect()->route('orders.success', $order)
                ->with('success', 'Order placed successfully!');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function success(Order $order): Response
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $order->load('items');

        return Inertia::render('Orders/Success', [
            'order' => $order,
        ]);
    }
}
