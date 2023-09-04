<?php

namespace App\Providers;

use App\Contracts\BuildersMenuHelperContract;
use App\Contracts\BuildersServiceContract;
use App\Contracts\EnvironmentsMenuHelperContract;
use App\Contracts\EnvironmentsServiceContract;
use App\Contracts\JsonConfigServiceContract;
use App\Helpers\BuildersMenuHelper;
use App\Helpers\EnvironmentsMenuHelper;
use App\Services\BuildersServiceService;
use App\Services\EnvironmentsService;
use App\Services\JsonConfigServiceService;
use Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider;
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
            JsonConfigServiceContract::class,
            JsonConfigServiceService::class
        );

        $this->app->singleton(
            EnvironmentsServiceContract::class,
            EnvironmentsService::class
        );

        $this->app->singleton(
            BuildersServiceContract::class,
            BuildersServiceService::class
        );

        $this->app->singleton(
            EnvironmentsMenuHelperContract::class,
            EnvironmentsMenuHelper::class
        );

        $this->app->singleton(
            BuildersMenuHelperContract::class,
            BuildersMenuHelper::class
        );

        if ($this->app->isLocal()) {
            $this->app->register(IdeHelperServiceProvider::class);
        }
    }
}
