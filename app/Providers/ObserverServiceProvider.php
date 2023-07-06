<?php

namespace App\Providers;

use App\Models\User;
use App\Observers\Order\OrderObserver;
use App\Observers\Transaction\TransactionObserver;
use App\Observers\User\UserObserver;
use Illuminate\Support\ServiceProvider;
use Modules\Order\Entities\Order;
use Modules\Wallet\Entities\Transaction;

class ObserverServiceProvider extends ServiceProvider
{
    private array $observers = [
        Transaction::class => TransactionObserver::class,
        User::class        => UserObserver::class,
        Order::class       => OrderObserver::class,
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
