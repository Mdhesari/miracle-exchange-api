<?php

namespace Modules\Account\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Account\Entities\Account;
use Modules\Account\Observers\AccountObserver;
use Modules\Order\Entities\Order;

class ObserverServiceProvider extends ServiceProvider
{
    private array $observers = [
        Account::class => AccountObserver::class,
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
