<?php

use App\Http\Controllers\{
    DashboardController,
    GuruController,
    KurikulumController,
    MataPelajaranController,
    TahunPelajaranController,
};
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Route::group(['middleware' => 'auth'], function () {

    // Role Admin
    Route::group(['middleware' => 'role:admin', 'prefix' => 'admin'], function () {
        Route::get('/', function () {
            return redirect()->route('dashboard');
        });

        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

        //Route Tahun Pelajaran
        Route::get('/tahunpelajaran/data', [TahunPelajaranController::class, 'data'])->name('tahunpelajaran.data');
        Route::resource('/tahunpelajaran', TahunPelajaranController::class)->except('create', 'edit');
        Route::put('/tahunpelajaran/update-status/{id}', [TahunPelajaranController::class, 'updateStatus'])->name('tahunpelajaran.update_status');

        // Route Kurikulum
        Route::get('/kurikulum/data', [KurikulumController::class, 'data'])->name('kurikulum.data');
        Route::resource('/kurikulum', KurikulumController::class)->except('create', 'edit');

        // Route Mata Pelajaran
        Route::get('/matapelajaran/data', [MataPelajaranController::class, 'data'])->name('matapelajaran.data');
        Route::resource('/matapelajaran', MataPelajaranController::class)->except('create', 'edit');

        // Route Guru
        Route::get('/guru/data', [GuruController::class, 'data'])->name('guru.data');
        Route::resource('/guru', GuruController::class)->except('create', 'edit');
    });
});
