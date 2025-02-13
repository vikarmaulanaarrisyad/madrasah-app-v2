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
        // dd($kurikulum);
        return view('dashboard.index', compact('guru', 'siswa', 'rombel', 'kurikulum'));
    }
}
