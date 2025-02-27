<?php

namespace App\Providers;

use App\Models\Aplikasi;
use App\Models\Artikel;
use App\Models\Event;
use App\Models\Sekolah;
use App\Models\TahunPelajaran;
use Illuminate\Support\ServiceProvider;
use Carbon\Carbon;

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

            // Event terbaru (3 event terbaru berdasarkan tanggal)
            $view->with('events', Event::orderBy('tanggal', 'desc')
                ->orderBy('waktu_mulai', 'desc')
                ->orderBy('waktu_selesai', 'desc')
                ->limit(3)
                ->get());

            // Event dalam rentang ±2 hari dari hari ini (maksimal 2 event)
            $today = Carbon::today();
            $view->with('upcomingEvents', Event::whereBetween('tanggal', [$today->copy()->subDays(2), $today->copy()->addDays(2)])
                ->orderBy('tanggal', 'asc')
                ->orderBy('waktu_mulai', 'asc')
                ->limit(2)
                ->get());
        });
    }
}
