<?php

namespace App\Providers;

use App\Models\Aplikasi;
use App\Models\Sekolah;
use Illuminate\Support\ServiceProvider;

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
        view()->composer('*', function ($view) {
            $view->with('aplikasi', Aplikasi::first());
            $view->with('sekolah', Sekolah::first());
        });
    }
}
