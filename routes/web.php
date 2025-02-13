<?php

use App\Http\Controllers\DashboardController;
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
    });
});
