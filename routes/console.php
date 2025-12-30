<?php

use App\Jobs\SendDailySalesReport;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Schedule::job(new SendDailySalesReport)->dailyAt('18:00')
    ->name('daily-sales-report')
    ->withoutOverlapping();


//i am setting this for manual dispatching of the daily sales report
Artisan::command('report:daily', function () {
    SendDailySalesReport::dispatch();
    $this->info('Daily sales report job dispatched.');
})->purpose('Send the daily sales report immediately');
