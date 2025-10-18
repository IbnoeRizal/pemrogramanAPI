<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Midtrans\Config;

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
        if ($this->app->environment('local') || $this->app->environment('production')) {
            URL::forceScheme('https');
        }

        Config::$serverKey = config("midtransAPI.server_key");
        Config::$clientKey = config("midtransAPI.client_key");
        Config::$isProduction = config("midtransAPI.is_production");
        Config::$isSanitized = config("midtransAPI.is_sanitized");
        Config::$is3ds = config("is3ds");
    }
}
