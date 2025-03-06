<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Siswa;
use App\Models\TahunPelajaran;

class DashboardController extends Controller
{
    public function index()
    {
        $guru = Guru::count();
        $siswa = Siswa::aktif()->count();
        $tahunPelajaran = TahunPelajaran::aktif()->first();
        $kurikulum = $tahunPelajaran?->kurikulum()->count() ?? 0;
        $rombel = $tahunPelajaran?->rombel()->count() ?? 0;
        $siswaLaki = Siswa::aktif()->whereHas('jenis_kelamin', function ($query) {
            $query->where('nama', 'Laki-laki');
        })->count();
        $siswaPerempuan  = Siswa::aktif()->whereHas('jenis_kelamin', function ($query) {
            $query->where('nama', 'Perempuan');
        })->count();
        return view('dashboard.index', compact('guru', 'siswa', 'rombel', 'kurikulum', 'siswaLaki', 'siswaPerempuan'));
    }
}
