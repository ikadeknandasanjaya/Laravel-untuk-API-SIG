<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\GisApiService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(GisApiService::class, function ($app) {
            return new GisApiService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
