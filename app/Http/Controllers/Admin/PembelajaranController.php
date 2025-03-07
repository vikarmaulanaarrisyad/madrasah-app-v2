<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\MataPelajaran;
use App\Models\Pembelajaran;
use App\Models\Rombel;
use App\Models\TahunPelajaran;
use Illuminate\Http\Request;

class PembelajaranController extends Controller
{
    public function index()
    {
        $tapelId = TahunPelajaran::aktif()->pluck('id')->first();
        $rombels = Rombel::where('tahun_pelajaran_id', $tapelId)->get();
        return view('admin.pembelajaran.index', compact('rombels'));
    }

    public function getData(Request $request)
    {
        $rombelId = $request->rombel_id;

        $data = Pembelajaran::with(['mata_pelajaran', 'guru', 'rombel'])
            ->where('rombel_id', $rombelId)
            ->get()
            ->map(function ($item) {
                return [
                    'mapel' => $item->mata_pelajaran->nama,
                    'guru'  => $item->guru->nama_lengkap ?? 'Belum Ditentukan'
                ];
            });
        return response()->json($data);
    }

    public function getMapelByRombel(Request $request)
    {
        $tapelId = TahunPelajaran::aktif()->pluck('id')->first();

        $rombel = Rombel::where('id', $request->rombel_id)
            ->where('tahun_pelajaran_id', $tapelId)
            ->first();

        if (!$rombel) {
            return response()->json(['success' => false, 'message' => 'Rombel tidak ditemukan.'], 404);
        }

        // Ambil data mata pelajaran dengan guru dari pembelajaran
        $mapels = MataPelajaran::where('kurikulum_id', $rombel->kurikulum_id)
            ->with([
                'pembelajaran' => function ($query) use ($request) {
                    $query->where('rombel_id', $request->rombel_id)
                        ->with('guru'); // Memuat relasi guru di dalam pembelajaran
                }
            ])
            ->get();

        return response()->json(['success' => true, 'data' => $mapels]);
    }


    public function getGuru()
    {
        return response()->json(Guru::all());
    }

    public function setGuru(Request $request)
    {
        // Cari ID guru berdasarkan nama lengkap
        $guru_id = Guru::where('nama_lengkap', $request->guru_nama)->value('id');

        if (!$guru_id) {
            return response()->json(['success' => false, 'message' => 'Guru tidak ditemukan.']);
        }

        // Gunakan updateOrCreate untuk memperbarui atau membuat data baru
        Pembelajaran::updateOrCreate(
            [
                'mata_pelajaran_id' => $request->mapel_id,
                'rombel_id' => $request->rombel_id,
                'status' => 1,
            ],
            ['guru_id' => $guru_id]
        );

        return response()->json(['success' => true, 'message' => 'Guru berhasil diperbarui.']);
    }
}
