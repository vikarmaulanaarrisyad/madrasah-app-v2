<?php

use App\Http\Controllers\{
    CetakAbsenGuruController,
    CetakAbsenSiswaController,
    DashboardController,
    GuruController,
    GuruJurnalController,
    JurnalGuruController,
    KelasController,
    KurikulumController,
    MataPelajaranController,
    RombelController,
    SiswaController,
    TahunPelajaranController,
};
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Route::group(['middleware' => 'auth'], function () {
    Route::get('/', function () {
        return redirect()->route('dashboard');
    });

    // Role Admin
    Route::group(['middleware' => 'role:admin', 'prefix' => 'admin'], function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
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
        Route::get('/guru/export-excel', [GuruController::class, 'exportEXCEL'])->name('guru.exportEXCEL');
        Route::post('/guru/import-excel', [GuruController::class, 'importEXCEL'])->name('guru.importEXCEL');
        Route::resource('/guru', GuruController::class)->except('create', 'edit');

        // Route Kelas
        Route::get('/kelas/data', [KelasController::class, 'data'])->name('kelas.data');
        Route::resource('/kelas', KelasController::class)->except('edit', 'create');

        // Route Siswa
        Route::get('/siswa/data', [SiswaController::class, 'data'])->name('siswa.data');
        Route::get('/siswa/export-excel', [SiswaController::class, 'exportEXCEL'])->name('siswa.exportEXCEL');
        Route::post('/siswa/import-excel', [SiswaController::class, 'importEXCEL'])->name('siswa.importEXCEL');
        Route::resource('/siswa', SiswaController::class)->except('edit', 'create');

        // Route Rombel
        Route::get('/rombel/data', [RombelController::class, 'data'])->name('rombel.data');
        Route::resource('/rombel', RombelController::class);
        Route::get('/rombel/{rombel_id}/siswa', [RombelController::class, 'getDataSiswa'])->name('rombel.getDataSiswa');
        Route::get('/rombel/{rombel_id}/siswa/data', [RombelController::class, 'getSiswaRombel'])->name('rombel.getSiswaRombel');
        Route::post('/rombel/add-siswa', [RombelController::class, 'addSiswa'])->name('rombel.addSiswa');
        Route::delete('/siswa/rombel/delete', [RombelController::class, 'removeSiswa'])->name('siswa.rombel.delete');

        // Route Jurnal Guru
        Route::get('/jurnal/data', [JurnalGuruController::class, 'data'])->name('jurnal.data');
        Route::resource('/jurnal', JurnalGuruController::class)->only('index');
        Route::get('/jurnal/export-pdf', [JurnalGuruController::class, 'exportPDF'])->name('jurnal.exportPDF');

        // Route Cetak Absen Siswa
        Route::get('/presensi-siswa/data', [CetakAbsenSiswaController::class, 'data'])->name('presensi.siswa.data');
        Route::get('/presensi-siswa', [CetakAbsenSiswaController::class, 'index'])->name('presensi.siswa.index');
        Route::get('/presensi-siswa/filter', [CetakAbsenSiswaController::class, 'filterPresensi'])->name('presensi.siswa.filter');
        Route::get('/presensi-siswa/download', [CetakAbsenSiswaController::class, 'downloadPdf'])->name('presensi.siswa.download');

        // Route Cetak Absen Guru
        Route::get('/presensi-guru/data', [CetakAbsenGuruController::class, 'data'])->name('presensi.guru.data');
        Route::get('/presensi-guru', [CetakAbsenGuruController::class, 'index'])->name('presensi.guru.index');
        Route::get('/presensi-guru/filter', [CetakAbsenGuruController::class, 'filterPresensi'])->name('presensi.guru.filter');
        Route::get('/presensi-guru/download', [CetakAbsenGuruController::class, 'downloadPdf'])->name('presensi.guru.download');
    });

    // Role Guru
    // Route::group(['middleware' => 'role:guru', 'prefix' => 'guru'], function () {
    //     Route::get('/', function () {
    //         return redirect()->route('dashboard');
    //     });

    //     Route::get('/guru/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    //     // Route Jurnal
    //     Route::get('guru/jurnal/data', [GuruJurnalController::class, 'data'])->name('guru.jurnal_data');
    //     Route::resource('/guru-jurnal', GuruJurnalController::class);
    // });
});
