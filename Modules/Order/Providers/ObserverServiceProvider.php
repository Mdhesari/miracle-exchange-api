<?php

namespace Modules\Order\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Order\Entities\Order;
use Modules\Order\Observers\OrderObserver;

class ObserverServiceProvider extends ServiceProvider
{
    private array $observers = [
        Order::class => OrderObserver::class,
    ];

    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        foreach ($this->observers as $model => $observer) {
            app($model)->observe($observer);
        }
    }
}
