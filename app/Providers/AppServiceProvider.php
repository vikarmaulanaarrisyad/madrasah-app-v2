<?php

namespace App\Providers;

use App\Models\Aplikasi;
use App\Models\Artikel;
use App\Models\Sekolah;
use App\Models\TahunPelajaran;
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
            $view->with('tapel', TahunPelajaran::aktif()->first());
            $view->with('sekolah', Sekolah::first());
            $view->with('artikelTerbaru', Artikel::where('status', 'publish')->orderBy('id', 'DESC')->first());
            $view->with('artikel', Artikel::where('status', 'publish')->orderBy('id', 'DESC')->take(3)->skip(1)->get());
            $view->with('artikelSlider', Artikel::where('status', 'publish')->orderBy('id', 'DESC')->take(10)->get());

            // Menambahkan artikel populer berdasarkan jumlah views tertinggi
            $view->with('populerPost', Artikel::where('status', 'publish')->orderBy('id', 'DESC')->take(5)->get());
        });
    }
}
