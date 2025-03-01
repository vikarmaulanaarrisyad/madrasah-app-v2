<?php

namespace App\Http\Controllers\Admin\K13;

use App\Http\Controllers\Controller;
use App\Models\K13KdMapel;
use App\Models\K13KkmMapel;
use App\Models\MataPelajaran;
use App\Models\TahunPelajaran;
use Illuminate\Http\Request;

class IntervalPredikatController extends Controller
{
    public function index()
    {
        return view('admin.k13.interval_predikat.index');
    }

    public function data()
    {
        $tapel = TahunPelajaran::aktif()->first();

        if (!$tapel) {
            return response()->json(['error' => 'Tahun Pelajaran aktif tidak ditemukan'], 404);
        }

        $dataKKM = K13KkmMapel::where('tahun_pelajaran_id', $tapel->id)
            ->orderBy('kelas_id', 'ASC')
            ->get();

        foreach ($dataKKM as $kkm) {
            $range = (100 - $kkm->kkm) / 3;
            $kkm->predikat_c = round($kkm->kkm, 0);
            $kkm->predikat_b = round($kkm->kkm + $range, 0);
            $kkm->predikat_a = round($kkm->kkm + ($range * 2), 0);
        }

        return datatables($dataKKM)
            ->addIndexColumn()
            ->addColumn('mapel', function ($q) {
                return $q->matapelajaran->nama;
            })
            ->addColumn('semester', function ($q) {
                // Ambil rombel pertama (jika ada) untuk mendapatkan tahun pelajaran
                $rombel = $q->kelas->rombel->first();
                return optional($rombel->tahun_pelajaran)->nama . ' ' . optional($rombel->tahun_pelajaran->semester)->nama ?? '-';
            })
            ->addColumn('kelas', function ($q) {
                return $q->kelas->nama ?? '-';
            })
            ->escapeColumns([])
            ->make(true);
    }
}
