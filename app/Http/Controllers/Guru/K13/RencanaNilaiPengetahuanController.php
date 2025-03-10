<?php

namespace App\Http\Controllers\Guru\K13;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\K13RencanaNilaiPengetahuan;
use App\Models\Pembelajaran;
use App\Models\Rombel;
use App\Models\TahunPelajaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RencanaNilaiPengetahuanController extends Controller
{
    public function data()
    {
        $tapel = TahunPelajaran::aktif()->first();
        $guru = Guru::where('user_id', Auth::user()->id)->first();

        // Perbaikan pada pluck()
        $rombel = Rombel::where('tahun_pelajaran_id', $tapel->id)->pluck('id')->toArray();

        $dataRencanaPenilaian = Pembelajaran::where('guru_id', $guru->id)
            ->whereHas('mata_pelajaran', function ($query) {
                $query->whereHas('kurikulum', function ($q) {
                    $q->where('nama', 'Kurikulum 2013'); // Sesuaikan dengan nama kurikulum di database
                });
            })
            ->whereIn('rombel_id', $rombel)
            ->where('status', 1)
            ->orderBy('mata_pelajaran_id', 'ASC')
            ->orderBy('rombel_id', 'ASC')
            ->get();

        foreach ($dataRencanaPenilaian as $penilaian) {
            $rencanaPenilaian = K13RencanaNilaiPengetahuan::where('pembelajaran_id', $penilaian->id)
                ->select('kode_penilaian')
                ->groupBy('kode_penilaian')
                ->get();
            $penilaian->jumlah_rencana_penilaian = $rencanaPenilaian->count();
        }

        return datatables($dataRencanaPenilaian)
            ->addIndexColumn()
            ->addColumn('mata_pelajaran', function ($row) {
                return $row->mata_pelajaran->nama; // Sesuaikan dengan relasi Mata Pelajaran
            })
            ->addColumn('rombel', function ($row) {
                return  $row->rombel->kelas->nama . ' '  . $row->rombel->nama; // Sesuaikan dengan relasi Rombel
            })
            ->addColumn('jumlah_rencana_penilaian', function ($row) {
                return $row->jumlah_rencana_penilaian;
            })
            ->addColumn('aksi', function ($row) {
                return '<a href="#" class="btn btn-sm btn-primary">Edit</a>';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function index()
    {
        return view('guru.k13.rencanapengetahuan.index');
    }
}
