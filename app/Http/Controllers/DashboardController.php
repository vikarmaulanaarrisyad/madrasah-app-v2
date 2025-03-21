<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\JadwalPelajaran;
use App\Models\Pembelajaran;
use App\Models\Siswa;
use App\Models\TahunPelajaran;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

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

        $user = Auth::user();

        if ($user->hasRole('admin')) {

            return view('dashboard.index', compact('guru', 'siswa', 'rombel', 'kurikulum', 'siswaLaki', 'siswaPerempuan'));
        } else {

            // $guruId = Guru::where('user_id', $user->id)->pluck('id')->first();

            // // Ambil daftar pembelajaran yang diampu oleh guru
            // $mataPelajaran = Pembelajaran::with('mata_pelajaran', 'rombel', 'guru')
            //     ->where('guru_id', $guruId)
            //     ->get();

            // // Ambil daftar rombel yang diajar oleh guru ini
            // $rombelIds = $mataPelajaran->pluck('rombel.id')->unique();

            // // Ambil hari ini dalam bahasa Indonesia
            // $hariIni = Carbon::now()->translatedFormat('l'); // Misalnya: "Senin", "Selasa", dll.

            // // Ambil jadwal pelajaran hanya untuk hari ini berdasarkan rombel yang diajar oleh guru
            // $jadwalPelajaran = JadwalPelajaran::with('mataPelajaran', 'jamPelajaran')->whereIn('rombel_id', $rombelIds)
            //     ->where('hari', $hariIni) // Filter berdasarkan hari ini
            //     ->orderBy('jam_pelajaran_id')
            //     ->get();

            $guruId = Guru::where('user_id', $user->id)->value('id'); // Lebih simpel dari pluck()->first()

            if (!$guruId) {
                return redirect()->back()->with('error', 'Guru tidak ditemukan.');
            }

            // Ambil daftar pembelajaran yang diampu oleh guru (ambil hanya ID yang dibutuhkan)
            $mataPelajaranIds = Pembelajaran::where('guru_id', $guruId)->pluck('mata_pelajaran_id');
            $rombelIds = Pembelajaran::where('guru_id', $guruId)->pluck('rombel_id')->unique();

            // Ambil hari ini dalam bahasa Indonesia
            $hariIni = Carbon::now()->translatedFormat('l'); // "Senin", "Selasa", dll.

            // Ambil jadwal pelajaran hanya untuk hari ini berdasarkan rombel dan mata pelajaran
            $jadwalPelajaran = JadwalPelajaran::with(['mataPelajaran', 'jamPelajaran'])
                ->whereIn('rombel_id', $rombelIds)
                ->whereIn('mata_pelajaran_id', $mataPelajaranIds) // Pastikan hanya mata pelajaran yang diampu
                ->where('hari', $hariIni)
                ->orderBy('jam_pelajaran_id')
                ->get();

            return view('guru.dashboard.index', compact('jadwalPelajaran', 'hariIni'));
        }
    }
}
