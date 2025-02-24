<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Rombel;
use App\Models\TahunPelajaran;
use Illuminate\Support\Facades\DB;

class KenaikanSiswaController extends Controller
{
    // public function index2()
    // {
    //     // Ambil tahun pelajaran aktif (terbaru)
    //     $tahunPelajaranAktif = TahunPelajaran::orderBy('nama', 'desc')->first();

    //     // Ambil tahun sebelumnya
    //     $tahunSebelumnya = TahunPelajaran::where('id', '<', $tahunPelajaranAktif->id)
    //         ->orderBy('nama', 'desc')
    //         ->first();

    //     // Ambil kelas dan rombel dari tahun sebelumnya
    //     $kelas = Rombel::where('tahun_pelajaran_id', $tahunSebelumnya->id)->orderBy('nama', 'asc')->get();
    //     $rombelSebelumnya = Rombel::where('tahun_pelajaran_id', $tahunSebelumnya->id)->orderBy('nama', 'asc')->get();
    //     $rombelBerikutnya = Rombel::where('tahun_pelajaran_id', $tahunPelajaranAktif->id)->orderBy('nama', 'asc')->get();

    //     // Ambil data siswa dari rombel sebelumnya
    //     $siswa = Siswa::where('level', $rombelSebelumnya->kelas->tingkat)
    //     whereHas('siswa_rombel', function ($q) use ($rombelSebelumnya) {
    //         $q->whereIn('rombel_id', $rombelSebelumnya->pluck('id')); // Perbaikan di sini
    //     })->get();

    //     return view('admin.kenaikan.index', compact('tahunSebelumnya', 'tahunPelajaranAktif', 'kelas', 'rombelSebelumnya', 'rombelBerikutnya', 'siswa'));
    // }

    public function index()
    {
        // ambil tahun pelajaran aktif
        $tahunPelajaranAktif = TahunPelajaran::aktif()->orderBy('nama', 'desc')->first();

        // ambil tahun sebelumnya
        $tahunSebelumnya = TahunPelajaran::where('id', '<', $tahunPelajaranAktif->id)->orderBy('nama', 'desc')->first();

        // ambil daftar rombel dari tahun sebelumnya
        $rombel = Rombel::
    }


    public function prosesKenaikan(Request $request)
    {
        $request->validate([
            'siswa_ids' => 'required|array',
            'kelas_tujuan' => 'required|exists:kelas,id',
            'rombel_tujuan' => 'required|exists:rombel,id',
        ]);

        try {
            DB::beginTransaction();

            $tahunPelajaranAktif = TahunPelajaran::orderBy('nama', 'desc')->first();

            // Update hanya siswa yang dipilih
            Siswa::whereIn('id', $request->siswa_ids)->update([
                'tahun_pelajaran_id' => $tahunPelajaranAktif->id,
                'kelas_id' => $request->kelas_tujuan,
                'rombel_id' => $request->rombel_tujuan,
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Proses kenaikan siswa berhasil dilakukan!'
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
