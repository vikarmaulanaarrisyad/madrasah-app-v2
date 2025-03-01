<?php

use App\Http\Controllers\{
    AplikasiController,
    ArtikelController,
    CetakAbsenGuruController,
    CetakAbsenSiswaController,
    CetakBukuIndukController,
    DashboardController,
    EventController,
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
use App\Http\Controllers\Admin\K13\ButirSikapController;
use App\Http\Controllers\Admin\K13\IntervalPredikatController;
use App\Http\Controllers\Admin\K13\Kkm13MapelController;
use App\Http\Controllers\Admin\K13\KkmMapelController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\Front\ArtikelFrontController;
use App\Http\Controllers\Front\EventFrontController;
use App\Http\Controllers\Front\PpdbFrontController;
use App\Http\Controllers\Front\ProfileFrontController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
    // return view('auth.login');
});

Route::get('/artikel', [ArtikelFrontController::class, 'index'])->name('front.artikel_index');
Route::get('/artikel/{slug}', [ArtikelFrontController::class, 'detail'])->name('front.artikel_detail');
Route::get('/profile/sejarah', [ProfileFrontController::class, 'sejarahIndex'])->name('front.sejarah_index');

Route::get('/event', [EventFrontController::class, 'index'])->name('front.event_index');
Route::get('/event/{slug}', [EventFrontController::class, 'detail'])->name('front.event_detail');

// Route : PPDB
// Route::get('/ppdb', [PpdbFrontController::class, 'index'])->name('front.ppdb_index');

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
        Route::get('/ajax/get-matapelajaran/{kelasId}', [MataPelajaranController::class, 'getMataPelajaran'])->name('matapelajaran.get');
        Route::get('/matapelajaran/data', [MataPelajaranController::class, 'data'])->name('matapelajaran.data');
        Route::resource('/matapelajaran', MataPelajaranController::class)->except('create', 'edit');

        // Route Guru
        Route::get('/guru/data', [GuruController::class, 'data'])->name('guru.data');
        Route::get('/guru/export-excel', [GuruController::class, 'exportEXCEL'])->name('guru.exportEXCEL');
        Route::post('/guru/import-excel', [GuruController::class, 'importEXCEL'])->name('guru.importEXCEL');
        Route::resource('/guru', GuruController::class)->except('create', 'edit');

        // Route Kelas
        Route::get('/ajax/kelas/data', [KelasController::class, 'getkelas'])->name('kelas.get');
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

        // Route Artikel
        Route::get('/artikel/data', [ArtikelController::class, 'data'])->name('artikel.data');
        Route::resource('/artikel', ArtikelController::class);
        Route::put('/artikel/update-status/{id}', [ArtikelController::class, 'updateStatus'])->name('artikel.update_status');

        // Event
        Route::get('/event/data', [EventController::class, 'data'])->name('event.data');
        Route::resource('/event', EventController::class);

        // Manajemen User
        Route::get('/users/data', [UserController::class, 'data'])->name('users.data');
        Route::post('users/reset-password/{id}', [UserController::class, 'resetPassword'])->name('users.resetPassword');
        Route::resource('/users', UserController::class);

        // Route KKM MAPEL K13
        Route::get('/k13kkm/data', [Kkm13MapelController::class, 'data'])->name('k13kkm.data');
        Route::resource('/k13kkm', Kkm13MapelController::class);
        Route::put('/k13kkm/update-kkm/{id}', [Kkm13MapelController::class, 'updateKkm'])->name('k13kkm.updatekkm');

        // Route Interval
        Route::get('/k13interval/data', [IntervalPredikatController::class, 'data'])->name('k13interval.data');
        Route::resource('/k13interval', IntervalPredikatController::class);

        // Route Butir Sikap
        Route::get('/k13sikap/data', [ButirSikapController::class, 'data'])->name('k13sikap.data');
        Route::resource('/k13sikap', ButirSikapController::class);
    });
});
