<?php

namespace App\Providers;

use App\Services\Navasan\Navasan;
use Illuminate\Support\ServiceProvider;

class NavasanServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton('navasan', function ($app) {
            return app(Navasan::class, [
                'apiKey' => config('navasan.apiKey'),
            ]);
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
