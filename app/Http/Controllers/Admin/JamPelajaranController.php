<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JamPelajaran;
use App\Models\TahunPelajaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class JamPelajaranController extends Controller
{
    public function index()
    {
        $tapelId = TahunPelajaran::aktif()->pluck('id')->first();
        $jamPelajaran = JamPelajaran::where('tahun_pelajaran_id', $tapelId)->orderBy('jam_ke')->get();
        return view('admin.jam_pelajaran.index', compact('jamPelajaran'));
    }

    public function create()
    {
        $tapelId = TahunPelajaran::aktif()->pluck('id')->first();
        $jamPelajaran = JamPelajaran::where('tahun_pelajaran_id', $tapelId)->orderBy('jam_ke')->get();
        return view('admin.jam_pelajaran.create', compact('jamPelajaran'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'jam_ke' => 'required|array',
            'jenis' => 'required|array',
            'durasi' => 'required|array',
            'mulai' => 'required|array',
            'selesai' => 'required|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'errors'  => $validator->errors(),
                'message' => 'Maaf, inputan yang Anda masukkan salah. Silakan periksa kembali dan coba lagi.',
            ], 422);
        }

        $tapelId = TahunPelajaran::aktif()->pluck('id')->first();

        foreach ($request->jam_ke as $index => $jamKe) {
            JamPelajaran::updateOrCreate(
                [
                    'tahun_pelajaran_id' => $tapelId,
                    'jam_ke' => $jamKe,
                ],
                [
                    'jenis' => $request->jenis[$index],
                    'durasi' => $request->durasi[$index],
                    'mulai' => $request->mulai[$index],
                    'selesai' => $request->selesai[$index],
                ]
            );
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Data jam pelajaran berhasil disimpan atau diperbarui.',
        ]);
    }

    public function destroy($id)
    {
        try {
            $jam = JamPelajaran::findOrFail($id);
            $jam->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Jam pelajaran berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menghapus jam pelajaran'
            ], 500);
        }
    }
}
