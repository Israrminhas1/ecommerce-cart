<?php

namespace App\Jobs;

use App\Mail\DailySalesReport;
use App\Models\User;
use App\Services\OrderService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendDailySalesReport implements ShouldQueue
{
    use Queueable;

    public function __construct() {}

    public function handle(OrderService $orderService): void
    {
        $salesData = $orderService->getTodaysSales();

        $admins = User::where('is_admin', true)->get();

        if ($admins->isEmpty()) {
            \Log::warning('No admin users found for daily sales report.');
            return;
        }

        foreach ($admins as $admin) {
            Mail::to($admin->email)->send(new DailySalesReport($salesData));
        }

        \Log::info('Daily sales report sent', [
            'date' => $salesData['date'],
            'total_orders' => $salesData['total_orders'],
            'total_revenue' => $salesData['total_revenue'],
        ]);
    }

    public function failed(\Throwable $exception): void
    {
        \Log::error('Failed to send daily sales report', [
            'error' => $exception->getMessage(),
        ]);
    }
}
