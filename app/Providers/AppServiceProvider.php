<?php

namespace App\Providers;

use App\Contracts\JsonConfigContract;
use App\Services\JsonConfigService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(
            JsonConfigContract::class,
            JsonConfigService::class
        );
    }
}
