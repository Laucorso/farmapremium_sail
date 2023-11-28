<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            'App\Repositories\CardRepositoryInterface',
            'App\Repositories\CardRepository',
        );
        $this->app->bind(
            \App\Repositories\PharmacyRepositoryInterface::class,
            \App\Repositories\PharmacyRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
