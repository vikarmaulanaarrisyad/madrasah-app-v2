<?php

namespace App\Http\Controllers\Guru\K13;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\K13NilaiPtsPas;
use App\Models\Pembelajaran;
use App\Models\Rombel;
use App\Models\TahunPelajaran;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NilaiPtsPasController extends Controller
{

    public function index()
    {
        return view('guru.k13.nilaiptspas.index');
    }

    public function data()
    {
        $tapel = TahunPelajaran::aktif()->first();
        $guru = Guru::where('user_id', Auth::id())->first();

        // Ambil semua ID rombel berdasarkan Tahun Pelajaran
        $rombelIds = Rombel::where('tahun_pelajaran_id', $tapel->id)->pluck('id');

        // Query dengan ORM
        $dataPenilaian = Pembelajaran::where('guru_id', $guru->id)
            ->whereIn('rombel_id', $rombelIds)
            ->where('status', 1)
            ->with(['mata_pelajaran', 'rombel']) // Menghindari N+1 Query
            ->withCount([
                'rombel as jumlah_anggota_rombel' => function ($query) {
                    $query->join('siswa_rombel', 'rombels.id', '=', 'siswa_rombel.rombel_id')
                        ->selectRaw('count(siswa_rombel.id)');
                },
                'nilaiPtsPas as jumlah_telah_dinilai'
            ])
            ->orderBy('mata_pelajaran_id', 'ASC')
            ->orderBy('rombel_id', 'ASC')
            ->get();

        return datatables($dataPenilaian)
            ->addIndexColumn()
            ->addColumn('mata_pelajaran', function ($row) {
                return $row->mata_pelajaran->nama ?? '-';
            })
            ->addColumn('kelas', function ($row) {
                return  $row->rombel->kelas->nama . ' ' . $row->rombel->nama ?? '-';
            })
            ->addColumn('aksi', function ($q) {
                $aksi = '';
                if ($q->jumlah_telah_dinilai == 0) {
                    $aksi .= '
                        <button onclick="addForm(`' . route('nilaiptspas.create', $q->id) . '`, ' . $q->id . ')"
                                class="btn btn-sm btn-primary" title="Tambah">
                            <i class="fas fa-plus"></i>
                        </button>';
                } else {
                    $aksi .= '
                        <button onclick="editForm(`' . route('nilaiptspas.edit', $q->id) . '`)"
                                class="btn btn-sm btn-warning" title="Edit">
                            <i class="fas fa-pencil-alt"></i>
                        </button>';
                }

                return $aksi;
            })
            ->rawColumns(['input_nilai', 'aksi'])
            ->escapeColumns([])
            ->make(true);
    }

    public function create($id)
    {
        // Cek apakah data pembelajaran ditemukan
        $pembelajaran = Pembelajaran::findOrFail($id);
        // Ambil data siswa berdasarkan rombel_id dari pembelajaran
        $dataAnggotaRombel = Rombel::with('siswa_rombel')->where('id', $pembelajaran->rombel_id)->get();
        // $dataAnggotaRombel = Rombel::with('siswa_rombel')->where('id', $pembelajaran->rombel_id)->first();

        $dataNilaiPtsPas = K13NilaiPtsPas::where('pembelajaran_id', $pembelajaran->id)->get();

        return view('guru.k13.nilaiptspas.create', compact('pembelajaran', 'dataAnggotaRombel'));
    }

    public function store(Request $request)
    {
        $tapel = TahunPelajaran::aktif()->first();

        if (is_null($request->anggota_rombel_id) || is_null($request->siswa_id)) {
            return response()->json(['message' => 'Data siswa tidak ditemukan'], 404);
        }

        foreach ($request->anggota_rombel_id as $index => $anggotaRombelId) {
            // Validasi nilai PTS & PAS harus antara 0 - 100
            if (($request->nilai_pts[$index] < 0 || $request->nilai_pts[$index] > 100) ||
                ($request->nilai_pas[$index] < 0 || $request->nilai_pas[$index] > 100)
            ) {
                return response()->json(['message' => 'Nilai harus berisi antara 0 s/d 100'], 400);
            }

            // Simpan data ke database dengan siswa_id
            K13NilaiPtsPas::create([
                'tahun_pelajaran_id' => $tapel->id,
                'pembelajaran_id' => $request->pembelajaran_id,
                'rombel_id' => $anggotaRombelId,
                'siswa_id' => $request->siswa_id[$index], // Tambahkan siswa_id di sini
                'nilai_pts' => $request->nilai_pts[$index],
                'nilai_pas' => $request->nilai_pas[$index],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        return response()->json(['message' => 'Nilai berhasil disimpan'], 201);
    }


    public function edit($id)
    {
        $pembelajaran = Pembelajaran::findOrFail($id);

        // Mengambil data anggota rombel berdasarkan rombel_id
        $dataAnggotaRombel = Rombel::with('siswa_rombel')->where('id', $pembelajaran->rombel_id)->firstOrFail();

        // Mengambil data nilai yang terkait dengan pembelajaran
        $dataNilaiPtsPas = K13NilaiPtsPas::where('pembelajaran_id', $pembelajaran->id)->get();

        return view('guru.k13.nilaiptspas.edit', compact('pembelajaran', 'dataNilaiPtsPas', 'dataAnggotaRombel'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'siswa_id' => 'required|array',
            'nilai_pts' => 'required|array',
            'nilai_pts.*' => 'nullable|numeric|min:0|max:100',
            'nilai_pas' => 'required|array',
            'nilai_pas.*' => 'nullable|numeric|min:0|max:100',
        ]);

        try {
            DB::beginTransaction();
            $tapel = TahunPelajaran::aktif()->first();

            foreach ($request->siswa_id as $index => $siswa_id) {

                $nilai = K13NilaiPtsPas::updateOrCreate(
                    [
                        'pembelajaran_id' => $id,
                        'siswa_id' => $siswa_id,
                        'tahun_pelajaran_id' => $tapel->id,
                    ],
                    [
                        'nilai_pts' => $request->nilai_pts[$index] ?? null,
                        'nilai_pas' => $request->nilai_pas[$index] ?? null,
                    ]
                );
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Data nilai berhasil diperbarui!'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
