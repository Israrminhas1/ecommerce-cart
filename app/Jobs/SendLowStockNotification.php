<?php

namespace App\Jobs;

use App\Mail\LowStockAlert;
use App\Models\LowStockNotification;
use App\Models\Product;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendLowStockNotification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Product $product
    ) {}

    public function handle(): void
    {
        // Check if already notified recently (within 24 hours)
        if (LowStockNotification::wasRecentlyNotified($this->product)) {
            return;
        }

        // Get admin users
        $admins = User::where('is_admin', true)->get();

        if ($admins->isEmpty()) {
            return;
        }

        // Send notification to each admin
        foreach ($admins as $admin) {
            Mail::to($admin->email)->send(new LowStockAlert($this->product));
        }

        // Record the notification
        LowStockNotification::recordNotification($this->product);
    }

    public function failed(\Throwable $exception): void
    {
        \Log::error('Failed to send low stock notification', [
            'product_id' => $this->product->id,
            'error' => $exception->getMessage(),
        ]);
    }
}
