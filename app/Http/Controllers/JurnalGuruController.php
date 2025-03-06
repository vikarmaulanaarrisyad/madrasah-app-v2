<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\JurnalGuru;
use App\Models\Rombel;
use App\Models\TahunPelajaran;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class JurnalGuruController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tapel = TahunPelajaran::aktif()->first();
        $rombel = Rombel::where('tahun_pelajaran_id', $tapel->id)->get();
        $guru = Guru::all();
        return view('admin.jurnal.index', compact('rombel', 'guru'));
    }

    public function getData(Request $request)
    {
        $query = JurnalGuru::with('guru', 'rombel', 'mata_pelajaran')
            ->when($request->has('guru') && $request->guru != "", function ($query) use ($request) {
                return $query->where('guru_id', $request->guru);
            })
            ->when(
                $request->has('startDate') && $request->startDate != "" && $request->has('endDate') && $request->endDate != "",
                function ($query) use ($request) {
                    return $query->whereBetween('tanggal', [$request->startDate, $request->endDate]);
                }
            )
            ->when(
                $request->has('rombel') && $request->rombel != "",
                function ($query) use ($request) {
                    return $query->where('rombel_id', $request->rombel);
                }
            )
            ->orderBy('tanggal', 'ASC');
        return $query;
    }

    public function data(Request $request)
    {
        $query = $this->getData($request);

        return datatables()->eloquent($query)
            ->addIndexColumn()
            ->editColumn('rombel', function ($q) {
                return $q->rombel && $q->rombel->kelas ? ($q->rombel->kelas->nama ?? '') . ' ' . ($q->rombel->nama ?? '') : '';
            })
            ->editColumn('mapel', function ($q) {
                return $q->mata_pelajaran ? $q->mata_pelajaran->nama :  '';
            })
            ->editColumn('guru', function ($q) {
                return $q->guru->nama_lengkap ?? '-';
            })
            ->escapeColumns([])
            ->make(true);
    }

    public function exportPDF(Request $request)
    {
        $jurnals = $this->getData($request)
            ->with('mata_pelajaran', 'rombel.kurikulum', 'guru') // Ambil relasi kurikulum
            ->orderBy('mata_pelajaran_id', 'ASC')
            ->get();

        if ($jurnals->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan.'
            ], 403);
        }

        // Ambil kurikulum dari jurnal pertama (asumsi semua jurnal dalam satu permintaan memiliki kurikulum yang sama)
        $kurikulum = optional($jurnals->first()->rombel->kurikulum)->nama ?? '';

        // Jika kurikulum adalah "Merdeka", gunakan file PDF khusus
        $view = ($kurikulum === 'Kurikulum Merdeka') ? 'admin.jurnal.pdf_merdeka' : 'admin.jurnal.pdf_kur13';

        if ($kurikulum === 'Kurikulum Merdeka') {
            // Buat file PDF
            $pdf = Pdf::loadView($view, compact('jurnals'))
                ->setPaper('a4', 'portrait')
                ->set_option('isPhpEnabled', true);
        } else {
            // Buat file PDF
            $pdf = Pdf::loadView($view, compact('jurnals'))
                ->setPaper('a4', 'landscape')
                ->set_option('isPhpEnabled', true);
        }


        $fileName = now()->format('Ymd_His') . '_Jurnal.pdf';
        return $pdf->stream($fileName);
    }
}
