<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Rombel;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\TahunPelajaran;
use Barryvdh\DomPDF\Facade\Pdf;

class CetakBukuIndukController extends Controller
{
    public function index()
    {
        $tapelAktif = TahunPelajaran::aktif()->first();

        $rombels = Rombel::where('tahun_pelajaran_id', $tapelAktif->id)->get();

        return view('admin.bukuinduk.index', compact('rombels'));
    }

    public function data(Request $request)
    {
        $query = Siswa::with(['siswa_rombel'])->whereHas('siswa_rombel', function ($q) use ($request) {
            $q->where('rombel_id', $request->rombelId);
        })->get();

        return datatables($query)
            ->addIndexColumn()
            ->addColumn('rombel', function ($q) {
                return $q->siswa_rombel()->first()->kelas->nama . ' ' . $q->siswa_rombel()->first()->nama;
            })
            ->addColumn('aksi', function ($q) {
                return '';
            })
            ->escapeColumns([])
            ->make(true);
    }

    public function downloadAll(Request $request)
    {
        // Validasi input
        $request->validate([
            'rombel_id' => 'required|exists:rombels,id'
        ]);

        // Ambil data siswa berdasarkan rombel_id
        $siswas = Siswa::with(['siswa_rombel'])->whereHas('siswa_rombel', function ($q) use ($request) {
            $q->where('rombel_id', $request->rombel_id);
        })->get();

        // Pastikan ada siswa dalam rombel
        if ($siswas->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Tidak ada siswa dalam rombel ini.'
            ], 404);
        }

        // Generate nama file unik
        $timestamp = now()->format('Ymd_His');
        $uniqueFileName = "buku_induk_{$timestamp}.pdf";

        // Load view ke PDF
        $pdf = Pdf::loadView('admin.bukuinduk.pdf', compact('siswas'));

        // Stream PDF langsung ke browser
        return $pdf->stream($uniqueFileName);
    }
}
