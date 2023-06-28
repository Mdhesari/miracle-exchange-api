<?php

namespace Modules\Market\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Market\Services\Navasan\Navasan;

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
