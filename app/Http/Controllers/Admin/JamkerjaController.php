<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JamKerja;
use App\Models\TahunPelajaran;
use Illuminate\Http\Request;

class JamkerjaController extends Controller
{
    public function index()
    {
        $tapel = TahunPelajaran::aktif()->first();
        $jamKerja = JamKerja::where('tahun_pelajaran_id', $tapel->id)->get();
        return view('admin.jam_kerja.index', compact('jamKerja'));
    }

    public function store(Request $request)
    {
        $tapel = TahunPelajaran::aktif()->first();
        $data = [];

        foreach ($request->jam_masuk as $hari => $jam_masuk) {
            $jam_keluar = $request->jam_keluar[$hari];

            $jamKerja = JamKerja::updateOrCreate(
                ['hari' => $hari, 'tahun_pelajaran_id' => $tapel->id],
                ['jam_masuk' => $jam_masuk, 'jam_keluar' => $jam_keluar, 'tahun_pelajaran_id' => $tapel->id]
            );

            $data[] = $jamKerja;
        }

        return response()->json([
            'message' => 'Data berhasil disimpan!',
            'data' => $data
        ]);
    }

    public function getJamKerja()
    {
        return response()->json(JamKerja::all());
    }
}
