<?php

namespace App\Providers;

use App\Models\orders;
use Illuminate\Support\ServiceProvider;

use App\Models\stock;
use App\Observers\OrdersObserver;
use App\Observers\StockObserver;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        stock::observe(StockObserver::class);
        orders::observe(OrdersObserver::class);
    }
}
