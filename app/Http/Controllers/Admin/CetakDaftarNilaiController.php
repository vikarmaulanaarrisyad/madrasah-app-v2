<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MataPelajaran;
use App\Models\Rombel;
use App\Models\Siswa;
use App\Models\TahunPelajaran;
use Illuminate\Http\Request;

class CetakDaftarNilaiController extends Controller
{
    public function index()
    {
        $tahunPelajaran = TahunPelajaran::aktif()->first();
        $rombels = Rombel::where('tahun_pelajaran_id', $tahunPelajaran->id)->get();

        return view('admin.daftarnilai.index', compact('rombels'));
    }

    public function filter(Request $request)
    {
        $rombelId = $request->rombel_id;
        $rombel = Rombel::where('id', $rombelId)->first();
        $mataPelajaran = MataPelajaran::where('id', $request->mata_pelajaran_id)->first();

        $siswa = Siswa::whereHas('siswa_rombel', function ($q) use ($request) {
            $q->where('rombel_id', $request->rombel_id);
        })
            ->with('nilai_merdeka')
            ->get();

        // Tentukan Header Tabel Sesuai Kurikulum
        if ($rombel->kurikulum->nama == 'Kurikulum Merdeka') {
            $header = view('admin.daftarnilai.header_merdeka', compact('rombel', 'mataPelajaran'))->render();
            $body = view('admin.daftarnilai.tabel_nilai_merdeka', compact('siswa', 'rombel', 'mataPelajaran'))->render();
        } else {
            $header = view('admin.daftarnilai.header_2013')->render();
            $body = view('admin.daftarnilai.tabel_nilai_2013', compact('siswa'))->render();
        }

        return response()->json([
            'header' => $header,
            'body' => $body
        ]);
    }

    public function getMataPelajaran(Request $request)
    {
        $rombel = Rombel::where('id', $request->rombel_id)->first();

        $mataPelajaran = MataPelajaran::where('kurikulum_id', $rombel->kurikulum_id)->get();

        return response()->json(['mata_pelajaran' => $mataPelajaran]);
    }
}
