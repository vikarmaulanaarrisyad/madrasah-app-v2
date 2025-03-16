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

    public function getMapelByRombel1(Request $request)
    {
        $request->validate([
            'rombel_id' => 'required|exists:rombels,id'
        ]);

        $tapelId = TahunPelajaran::aktif()->pluck('id')->first();

        if (!$tapelId) {
            return response()->json(['success' => false, 'message' => 'Tahun Pelajaran aktif tidak ditemukan.'], 404);
        }

        $rombel = Rombel::where('id', $request->rombel_id)
            ->where('tahun_pelajaran_id', $tapelId)
            ->first();

        if (!$rombel) {
            return response()->json(['success' => false, 'message' => 'Rombel tidak ditemukan.'], 404);
        }

        $mapels = MataPelajaran::where('kurikulum_id', $rombel->kurikulum_id)
            ->with([
                'pembelajaran' => function ($query) use ($request) {
                    $query->where('rombel_id', $request->rombel_id)
                        ->with('guru');
                }
            ])
            ->get()
            ->map(function ($mapel) {
                $mapel->pembelajaran = $mapel->pembelajaran ?? []; // Pastikan selalu array
                return $mapel;
            });

        return response()->json(['success' => true, 'data' => $mapels]);
    }

    public function getMapelByRombel(Request $request)
    {
        $request->validate([
            'rombel_id' => 'required|exists:rombels,id'
        ]);

        $tapelId = TahunPelajaran::aktif()->pluck('id')->first();

        if (!$tapelId) {
            return response()->json(['success' => false, 'message' => 'Tahun Pelajaran aktif tidak ditemukan.'], 404);
        }

        $rombel = Rombel::where('id', $request->rombel_id)
            ->where('tahun_pelajaran_id', $tapelId)
            ->first();

        if (!$rombel) {
            return response()->json(['success' => false, 'message' => 'Rombel tidak ditemukan.'], 404);
        }

        $mapels = MataPelajaran::where('kurikulum_id', $rombel->kurikulum_id)
            ->with([
                'pembelajaran' => function ($query) use ($request) {
                    $query->where('rombel_id', $request->rombel_id)
                        ->with('guru');
                }
            ])
            ->get()
            ->map(function ($mapel) {
                $mapel->pembelajaran = collect($mapel->pembelajaran)->map(function ($pembelajaran) {
                    if (is_object($pembelajaran)) { // Pastikan ini objek, bukan ID/integer
                        return [
                            'guru_id' => $pembelajaran->guru_id,
                            'jamke' => is_string($pembelajaran->jamke)
                                ? explode(',', $pembelajaran->jamke) // Ubah string "1,2" jadi array [1,2]
                                : (is_array($pembelajaran->jamke) ? $pembelajaran->jamke : []), // Gunakan langsung jika array
                        ];
                    }
                    return null; // Jika bukan objek, buang nilai ini
                })->filter(); // Hapus nilai null jika ada

                return $mapel;
            });


        return response()->json(['success' => true, 'data' => $mapels]);
    }

    public function getGuru()
    {
        return response()->json(Guru::all());
    }

    public function setGuru1(Request $request)
    {
        // Cari ID guru berdasarkan nama lengkap
        $guru_id = Guru::where('id', $request->guru_id)->pluck('id')->first();

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
            [
                'guru_id' => $guru_id,
                'jamke' => $request->jam_ke ?? null
            ]
        );

        return response()->json(['success' => true, 'message' => 'Guru berhasil diperbarui.']);
    }

    public function setGuru(Request $request)
    {
        // Validasi input
        $request->validate([
            'mapel_id' => 'required|exists:mata_pelajarans,id',
            'rombel_id' => 'required|exists:rombels,id',
            'guru_id' => 'required|exists:gurus,id',
            'jam_ke' => 'required', // Pastikan jam_ke adalah array
        ]);

        // Cari ID guru berdasarkan request
        $guru = Guru::find($request->guru_id);
        if (!$guru) {
            return response()->json(['success' => false, 'message' => 'Guru tidak ditemukan.'], 404);
        }

        // Pastikan jam_ke adalah array
        $jam_ke = is_string($request->jam_ke) ? json_decode($request->jam_ke, true) : $request->jam_ke;
        if (!is_array($jam_ke)) {
            return response()->json(['success' => false, 'message' => 'Format jam ke tidak valid.'], 400);
        }

        // Ambil semua jam ke yang sudah dipakai dalam rombel yang sama
        $existingJamKe = Pembelajaran::where('rombel_id', $request->rombel_id)
            ->where('mata_pelajaran_id', '!=', $request->mapel_id) // Hindari pengecekan pada mapel yang sama
            ->pluck('jamke')
            ->map(function ($jam) {
                return json_decode($jam, true) ?: []; // Pastikan tidak ada null
            })
            ->collapse()
            ->unique()
            ->toArray();

        // Cek apakah ada jam yang bertabrakan
        $conflict = array_intersect($jam_ke, $existingJamKe);
        if (!empty($conflict)) {
            return response()->json([
                'success' => false,
                'message' => 'Jam ke ' . implode(', ', $conflict) . ' sudah terisi. Pilih jam yang tersedia.',
            ], 400);
        }

        // Simpan atau update pembelajaran
        Pembelajaran::updateOrCreate(
            [
                'mata_pelajaran_id' => $request->mapel_id,
                'rombel_id' => $request->rombel_id,
                'status' => 1,
            ],
            [
                'guru_id' => $guru->id,
                'jamke' => !empty($jam_ke) ? implode(',', $jam_ke) : null, // Pastikan tidak menyimpan array kosong
            ]
        );

        return response()->json(['success' => true, 'message' => 'Guru berhasil diperbarui.']);
    }
}
