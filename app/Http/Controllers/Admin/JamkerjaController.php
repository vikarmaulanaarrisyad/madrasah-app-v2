<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JamKerja;
use App\Models\JamKerjaKhusus;
use App\Models\TahunPelajaran;
use Carbon\Carbon;
use Illuminate\Http\Request;

class JamkerjaController extends Controller
{
    public function index()
    {
        $tapel = TahunPelajaran::aktif()->first();
        $jamKerja = JamKerja::where('tahun_pelajaran_id', $tapel->id)->get();
        return view('admin.jam_kerja.index', compact('jamKerja'));
    }

    public function store1(Request $request)
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

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'jam_masuk' => 'array',
            'jam_keluar' => 'array',
            'jam_masuk_ramadhan' => 'array',
            'jam_keluar_ramadhan' => 'array',
        ]);

        foreach (['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $hari) {
            // Simpan jam kerja reguler
            JamKerja::updateOrCreate(
                ['hari' => $hari, 'is_ramadhan' => false],
                [
                    'jam_masuk' => $validatedData['jam_masuk'][$hari] ?? null,
                    'jam_keluar' => $validatedData['jam_keluar'][$hari] ?? null,
                ]
            );

            // Simpan jam kerja Ramadhan
            JamKerja::updateOrCreate(
                ['hari' => $hari, 'is_ramadhan' => true],
                [
                    'jam_masuk' => $validatedData['jam_masuk_ramadhan'][$hari] ?? null,
                    'jam_keluar' => $validatedData['jam_keluar_ramadhan'][$hari] ?? null,
                ]
            );
        }

        return response()->json([
            'message' => 'Jam kerja berhasil diperbarui!',
            'data' => JamKerja::all()
        ]);
    }

    public function storeKhusus(Request $request)
    {
        // Validasi input
        $request->validate([
            'tanggal' => 'required|date',
            'jam_masuk' => 'required',
            'jam_keluar' => 'required|after:jam_masuk',
        ]);

        // Simpan atau update jam kerja khusus
        $jamKerja = JamKerjaKhusus::updateOrCreate(
            ['tanggal' => $request->tanggal],
            ['jam_masuk' => $request->jam_masuk, 'jam_keluar' => $request->jam_keluar]
        );

        return response()->json([
            'message' => 'Jam kerja khusus berhasil disimpan!',
            'data' => $jamKerja
        ]);
    }


    public function getJamKerja()
    {
        return response()->json(JamKerja::all());
    }

    public function getJamKerjaByDate($tanggal)
    {
        // Cek apakah ada jam kerja khusus
        $jamKhusus = JamKerjaKhusus::where('tanggal', $tanggal)->first();

        if ($jamKhusus) {
            return response()->json([
                'type' => 'khusus',
                'jam_masuk' => $jamKhusus->jam_masuk,
                'jam_keluar' => $jamKhusus->jam_keluar,
            ]);
        }

        // Jika tidak ada, ambil jam kerja reguler berdasarkan hari
        $hari = Carbon::parse($tanggal)->translatedFormat('l'); // Ambil hari dari tanggal
        $jamReguler = JamKerja::where('hari', $hari)->first();

        return response()->json([
            'type' => 'reguler',
            'jam_masuk' => $jamReguler->jam_masuk ?? '08:00',
            'jam_keluar' => $jamReguler->jam_keluar ?? '16:00',
        ]);
    }
}
