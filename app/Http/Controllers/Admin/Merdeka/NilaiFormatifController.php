<?php

namespace App\Http\Controllers\Admin\Merdeka;

use App\Http\Controllers\Controller;
use App\Models\MataPelajaran;
use App\Models\MerdekaNilaiAkhir;
use App\Models\MerdekaNilaiFormatif;
use App\Models\NilaiRaport;
use App\Models\Rombel;
use App\Models\TahunPelajaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NilaiFormatifController extends Controller
{
    // Mendapatkan data siswa by rombel
    public function siswaData(Request $request)
    {
        $rombelId = $request->rombel_id; // Ambil rombel_id dari request

        // Ambil data siswa yang terhubung dengan rombel tertentu
        $data = Rombel::where('id', $rombelId)
            ->first() // Ambil 1 data Rombel
            ->siswa_rombel() // Ambil hanya siswa dari rombel tersebut
            ->get();

        return datatables($data)
            ->addIndexColumn() // Tambahkan nomor urut otomatis
            ->editColumn('nama', function ($siswa) {
                return $siswa->nama_lengkap; // Pastikan langsung mengambil dari objek siswa
            })
            ->editColumn('nilai', function ($siswa) use ($rombelId) {
                $nilaiFormatif = MerdekaNilaiFormatif::where('siswa_id', $siswa->id)
                    ->where('rombel_id', $rombelId)
                    ->pluck('nilai');

                return $nilaiFormatif->implode(', '); // Menampilkan semua nilai dipisahkan dengan koma
            })

            ->make(true);
    }

    public function index(Request $request)
    {
        $mataPelajaran = MataPelajaran::findOrfail($request->mata_pelajaran_id);
        $rombel = Rombel::findOrfail($request->rombel_id);

        return view('guru.merdeka.nilaisumatif.index', compact('rombel', 'mataPelajaran'));
    }

    public function create($rombel_id, $mapel_id)
    {
        $mataPelajaran = MataPelajaran::findOrfail($mapel_id);
        $rombel = Rombel::with('siswa_rombel')->where('id', $rombel_id)->first();

        return view('guru.merdeka.nilaisumatif.create', compact('rombel', 'mataPelajaran'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'rombel_id' => 'required|integer|exists:rombels,id',
            'mata_pelajaran_id' => 'required|integer|exists:mata_pelajarans,id',
            'siswa_id' => 'required|array',
            'materi' => 'nullable',
            'nilai' => 'required|array',
            'nilai.*' => 'numeric|min:0|max:100', // Pastikan nilai antara 0-100
        ]);

        // Pastikan jumlah siswa_id dan nilai sama
        if (count($validated['siswa_id']) !== count($validated['nilai'])) {
            return response()->json(['message' => 'Jumlah siswa dan nilai tidak cocok!'], 400);
        }

        // Cek Tahun Pelajaran Aktif
        $tapel = TahunPelajaran::aktif()->first();
        if (!$tapel) {
            return response()->json(['message' => 'Tahun Pelajaran Aktif tidak ditemukan!'], 400);
        }

        // Cek kurikulum pada rombel
        $rombel = Rombel::findOrFail($request->rombel_id);
        $kurikulum = optional($rombel->kurikulum)->nama;

        // Cek SUM terakhir berdasarkan mata pelajaran
        $lastSUM = MerdekaNilaiFormatif::where('mata_pelajaran_id', $validated['mata_pelajaran_id'])
            ->max('sum'); // Ambil SUM terbesar

        $newSUM = $lastSUM ? $lastSUM + 1 : 1; // Jika ada, tambah 1; jika tidak, mulai dari 1

        foreach ($validated['siswa_id'] as $key => $siswaId) {
            MerdekaNilaiFormatif::updateOrCreate(
                [
                    'tahun_pelajaran_id' => $tapel->id,
                    'rombel_id' => $validated['rombel_id'],
                    'mata_pelajaran_id' => $validated['mata_pelajaran_id'],
                    'materi' => $validated['materi'],
                    'siswa_id' => $siswaId,
                    'sum' => $newSUM, // Set nilai SUM
                ],
                [
                    'nilai' => $validated['nilai'][$key]
                ]
            );
        }

        return response()->json([
            'message' => 'Data berhasil disimpan!',
        ], 201);
    }


    public function destroy($id)
    {
        try {
            $nilai = MerdekaNilaiFormatif::findOrFail($id);

            // Hapus semua nilai dengan PH yang sama untuk setiap siswa
            MerdekaNilaiFormatif::where('sum', $nilai->sum)
                ->where('rombel_id', $nilai->rombel_id)
                ->where('mata_pelajaran_id', $nilai->mata_pelajaran_id)
                ->delete();

            // Ambil kembali semua nilai yang tersisa, urutkan berdasarkan siswa_id dan PH
            $nilaiTersisa = MerdekaNilaiFormatif::where('rombel_id', $nilai->rombel_id)
                ->where('mata_pelajaran_id', $nilai->mata_pelajaran_id)
                ->orderBy('siswa_id', 'asc')  // Urutkan per siswa
                ->orderBy('sum', 'asc') // Pastikan SUM juga diurutkan
                ->get();

            // **Perbaiki sum agar tetap berurutan per siswa**
            $sumPerSiswa = []; // Menyimpan SUM terbaru per siswa

            foreach ($nilaiTersisa as $data) {
                $siswa_id = $data->siswa_id;

                if (!isset($sumPerSiswa[$siswa_id])) {
                    $sumPerSiswa[$siswa_id] = 1; // sum mulai dari 1 per siswa
                }

                // Update hanya jika perlu
                if ($data->sum !== $sumPerSiswa[$siswa_id]) {
                    $data->update(['sum' => $sumPerSiswa[$siswa_id]]);
                }

                $sumPerSiswa[$siswa_id]++; // Increment SUM untuk siswa ini
            }

            return response()->json([
                'message' => 'Nilai berhasil dihapus!',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat menghapus nilai.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function edit($rombel_id, $mapel_id, $sum)
    {
        $rombel = Rombel::with('siswa_rombel')->findOrFail($rombel_id);
        $mataPelajaran = MataPelajaran::findOrFail($mapel_id);

        $nilaiSUM = MerdekaNilaiFormatif::where('rombel_id', $rombel_id)
            ->where('mata_pelajaran_id', $mapel_id)
            ->where('sum', $sum)
            ->get();

        return view('guru.merdeka.nilaisumatif.edit', compact('rombel', 'mataPelajaran', 'nilaiSUM', 'sum'));
    }

    public function update(Request $request, $rombel_id, $mata_pelajaran_id, $sum)
    {
        try {
            // Validasi input
            $request->validate([
                'siswa_id' => 'required|array',
                'nilai' => 'required|array',
                'nilai.*' => 'nullable|numeric|min:0|max:100',
            ]);

            // Loop untuk update nilai berdasarkan siswa_id dan sum
            foreach ($request->siswa_id as $index => $siswa_id) {
                MerdekaNilaiFormatif::updateOrCreate(
                    [
                        'siswa_id' => $siswa_id,
                        'sum' => $sum,
                        'mata_pelajaran_id' => $mata_pelajaran_id,
                    ],
                    [
                        'nilai' => $request->nilai[$index],
                        'materi' => $request->materi, // Simpan materi
                    ]
                );
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Nilai berhasil diperbarui!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function kirim($rombel_id, $mata_pelajaran_id)
    {
        try {
            DB::beginTransaction(); // Mulai transaction

            // Ambil nilai harian yang sesuai
            $nilaiFormatif = MerdekaNilaiFormatif::where('rombel_id', $rombel_id)
                ->where('mata_pelajaran_id', $mata_pelajaran_id)
                ->get();

            if ($nilaiFormatif->isEmpty()) {
                return response()->json(['message' => 'Tidak ada nilai yang bisa dikirim!'], 400);
            }

            // Ambil data Tahun Pelajaran Aktif
            $tapel = TahunPelajaran::aktif()->first();

            if (!$tapel) {
                return response()->json(['message' => 'Tahun pelajaran tidak ditemukan!'], 400);
            }

            // Update status semua nilai menjadi "terkirim"
            MerdekaNilaiFormatif::where('rombel_id', $rombel_id)
                ->where('mata_pelajaran_id', $mata_pelajaran_id)
                ->update(['status' => 'terkirim']);

            // Loop setiap nilai harian dan simpan ke `MerdekaNilaiAkhir`
            foreach ($nilaiFormatif as $formatif) {
                MerdekaNilaiAkhir::updateOrCreate(
                    [
                        'tahun_pelajaran_id' => $tapel->id,
                        'siswa_id' => $formatif->siswa_id,
                        'rombel_id' => $rombel_id,
                        'mata_pelajaran_id' => $mata_pelajaran_id,
                    ],
                    [
                        'sum' => $formatif->sum, // Menambah sum setiap kali update
                        'materi' => $formatif->materi,
                        'nilai' => $formatif->nilai,
                        'sumatif_tengah_semester' => $formatif->sumatif_tengah_semester ?? 0,
                        'sumatif_akhir_semester' => $formatif->sumatif_akhir_semester ?? 0,
                    ]
                );
            }

            DB::commit(); // Commit jika semua berhasil
            return response()->json(['message' => 'Nilai berhasil dikirim ke MerdekaNilaiAkhir!']);
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback jika terjadi kesalahan
            return response()->json(['message' => 'Terjadi kesalahan saat mengirim nilai!', 'error' => $e->getMessage()], 500);
        }
    }

    public function batalKirim($rombel_id, $mata_pelajaran_id)
    {
        try {
            DB::beginTransaction(); // Mulai transaction

            $tapel = TahunPelajaran::aktif()->first();

            // Kembalikan status nilai agar bisa diedit kembali
            MerdekaNilaiFormatif::where('rombel_id', $rombel_id)
                ->where('mata_pelajaran_id', $mata_pelajaran_id)
                ->update(['status' => null]);

            // Hapus data yang telah dikirim ke MerdekaNilaiAkhir
            MerdekaNilaiAkhir::where('rombel_id', $rombel_id)
                ->where('mata_pelajaran_id', $mata_pelajaran_id)
                ->where('tahun_pelajaran_id', $tapel->id)
                ->delete();

            DB::commit(); // Commit jika semua berhasil
            return response()->json(['message' => 'Pengiriman nilai dibatalkan dan data berhasil dihapus!']);
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback jika terjadi kesalahan
            return response()->json(['message' => 'Terjadi kesalahan saat membatalkan pengiriman nilai!', 'error' => $e->getMessage()], 500);
        }
    }


    private function hitungPredikat($nilai)
    {
        if ($nilai >= 90) {
            return 'A';
        } elseif ($nilai >= 75) {
            return 'B';
        } elseif ($nilai >= 60) {
            return 'C';
        } else {
            return 'D';
        }
    }
}
