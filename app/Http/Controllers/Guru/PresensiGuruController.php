<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\AbsensiGuru;
use App\Models\Guru;
use App\Models\HariLibur;
use App\Models\JamKerja;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PresensiGuruController extends Controller
{
    public function index1()
    {
        $user = Auth::user();

        $guru = Guru::where('user_id', $user->id)->first();

        // Ambil nama hari sekarang dalam bahasa Indonesia
        $hariIni = Carbon::now()->locale('id')->translatedFormat('l');

        // Ambil data jam kerja berdasarkan nama hari
        $jamKerja = JamKerja::where('hari', $hariIni)->first();


        return view('guru.presensi.guru.index', compact('jamKerja'));
    }

    public function index()
    {
        $user = Auth::user();
        $guru = Guru::where('user_id', $user->id)->first();

        // Ambil nama hari sekarang dalam bahasa Indonesia
        $hariIni = Carbon::now()->locale('id')->translatedFormat('l');

        // Cek apakah sekarang bulan Ramadhan dalam kalender Hijriyah
        $isRamadhan = isRamadhan(); // 9 = Ramadhan dalam kalender Hijriyah

        // Ambil jam kerja berdasarkan hari dan status Ramadhan
        $jamKerja = JamKerja::where('hari', $hariIni)
            ->where('is_ramadhan', $isRamadhan)
            ->first();
        return view('guru.presensi.guru.index', compact('jamKerja'));
    }


    public function data()
    {

        $user = Auth::user();

        $guru = Guru::where('user_id', $user->id)->first();

        $presensi = AbsensiGuru::where('guru_id', $guru->id)->latest()->first();

        return response()->json([
            'success' => true,
            'jam_masuk' => $presensi ? $presensi->waktu_masuk : null,
            'jam_pulang' => $presensi ? $presensi->waktu_keluar : null
        ]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $guru = Guru::where('user_id', $user->id)->first();

        $tanggal = now()->format('Y-m-d');

        $presensi = AbsensiGuru::firstOrCreate(
            ['guru_id' => $guru->id, 'tgl_presensi' => $tanggal, 'status' => 'H'],
            // ['work_from_home' => $request->wfh ? true : false]
        );

        if ($request->jenis === 'masuk') {
            if ($presensi->waktu_masuk) {
                return response()->json(['message' => 'Anda sudah absen masuk hari ini!'], 400);
            }
            $presensi->waktu_masuk = now()->format('H:i:s');
        } elseif ($request->jenis === 'pulang') {
            if ($presensi->waktu_keluar) {
                return response()->json(['message' => 'Anda sudah absen pulang hari ini!'], 400);
            }
            $presensi->waktu_keluar = now()->format('H:i:s');
        }

        $presensi->save();

        return response()->json(['message' => 'Presensi berhasil disimpan!', 'data' => $presensi], 201);
    }

    public function cekHariLibur(Request $request)
    {
        $tanggal = now()->format('Y-m-d');
        $hariLibur = HariLibur::where('tanggal', $tanggal)->first(); // Ambil data lengkap

        if ($hariLibur) {
            return response()->json([
                'status' => 'libur',
                'message' => 'Tanggal ini adalah ' . $hariLibur->keterangan
            ]);
        }

        return response()->json(['status' => 'buka']);
    }
}
