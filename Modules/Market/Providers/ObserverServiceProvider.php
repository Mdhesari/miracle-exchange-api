<?php

namespace Modules\Market\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Market\Entities\Market;
use Modules\Market\Observers\MarketObserver;
use Modules\Order\Entities\Order;

class ObserverServiceProvider extends ServiceProvider
{
    private array $observers = [
        Market::class => MarketObserver::class,
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
