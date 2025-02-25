<?php

use App\Http\Controllers\{
    AplikasiController,
    CetakAbsenGuruController,
    CetakAbsenSiswaController,
    CetakBukuIndukController,
    DashboardController,
    GuruController,
    GuruJurnalController,
    JurnalGuruController,
    KategoriController,
    KelasController,
    KenaikanSiswaController,
    KkmController,
    KurikulumController,
    MataPelajaranController,
    RombelController,
    SekolahController,
    SiswaController,
    TahunPelajaranController,
    UserProfileInformationController,
};
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
    // return view('auth.login');
});

Route::group(['middleware' => 'auth'], function () {
    // Route::get('/', function () {
    //     return redirect()->route('dashboard');
    // });

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
        Route::get('/siswa/{id}/detail', [SiswaController::class, 'detail'])->name('siswa.detail');
        Route::post('/siswa/orangtua/update', [SiswaController::class, 'updateOrtu'])->name('siswa.update_ortu');

        // Route Proses Kenaikan Siswa
        Route::get('/kenaikan-siswa', [KenaikanSiswaController::class, 'index'])->name('kenaikan-siswa.index');
        Route::get('/kenaikan-siswa/get-siswa', [KenaikanSiswaController::class, 'getSiswa'])->name('kenaikan-siswa.get-siswa');
        Route::post('/kenaikan-siswa/proses', [KenaikanSiswaController::class, 'prosesKenaikan'])->name('kenaikan-siswa.proses');
        Route::post('/kenaikan-siswa/batal', [KenaikanSiswaController::class, 'batalKenaikan'])->name('kenaikan-siswa.batal');

        Route::get('/naikkan-siswa/{rombel_id}', [SiswaController::class, 'naikkanSiswaPerRombel'])->name('siswa,kenaikanSiswa');
        Route::get('/batalkan-kenaikan/{rombel_id}', [SiswaController::class, 'batalkanKenaikanPerRombel'])->name('siswa.batalkanKenaikkanSiswa');

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

        Route::get('/bukuinduk/data', [CetakBukuIndukController::class, 'data'])->name('bukuinduk.data');
        Route::get('/bukuinduk', [CetakBukuIndukController::class, 'index'])->name('bukuinduk.index');
        Route::get('/bukuinduk/download_semua', [CetakBukuIndukController::class, 'downloadAll'])->name('bukuinduk.download_all');

        // Route Sekolah
        Route::get('/sekolah', [SekolahController::class, 'index'])->name('sekolah.index');
        Route::put('/sekolah/{id}/update', [SekolahController::class, 'update'])->name('sekolah.update');
        Route::get('/aplikasi', [AplikasiController::class, 'index'])->name('aplikasi.index');
        Route::put('/aplikasi/{id}/update', [AplikasiController::class, 'update'])->name('aplikasi.update');
        Route::get('/user/profile', [UserProfileInformationController::class, 'show'])
            ->name('profile.show');

        // Route Kategori
        Route::get('/kategori/data', [KategoriController::class, 'data'])->name('kategori.data');
        Route::resource('/kategori', KategoriController::class);

        // Route Nilai Siswa
        Route::resource('/k13kkm', KkmController::class);
    });
});
