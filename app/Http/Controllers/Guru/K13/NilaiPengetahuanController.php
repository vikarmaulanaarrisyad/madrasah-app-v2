<?php

namespace App\Http\Controllers\Guru\K13;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\K13KkmMapel;
use App\Models\K13NilaiPengetahuan;
use App\Models\K13RencanaNilaiPengetahuan;
use App\Models\MataPelajaran;
use App\Models\NilaiHarian;
use App\Models\NilaiRaport;
use App\Models\Pembelajaran;
use App\Models\Rombel;
use App\Models\TahunPelajaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class NilaiPengetahuanController extends Controller
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
            ->editColumn('nilai', function ($siswa) {
                // Ambil semua nilai harian untuk siswa tertentu
                $nilaiHarian = NilaiHarian::where('siswa_id', $siswa->id)->pluck('nilai');

                // Gabungkan nilai menjadi string atau tampilkan jumlahnya
                return $nilaiHarian->implode(', '); // Jika ingin menampilkan semua nilai dipisahkan dengan koma
                // return $nilaiHarian->count(); // Jika hanya ingin menampilkan jumlah nilai PH
            })

            ->make(true);
    }

    public function index(Request $request)
    {
        $mataPelajaran = MataPelajaran::findOrfail($request->mata_pelajaran_id);
        $rombel = Rombel::findOrfail($request->rombel_id);
        return view('guru.k13.nilaipengetahuan.index', compact('rombel', 'mataPelajaran'));
    }

    public function create($rombel_id, $mapel_id)
    {
        $mataPelajaran = MataPelajaran::findOrfail($mapel_id);
        $rombel = Rombel::with('siswa_rombel')->where('id', $rombel_id)->first();

        return view('guru.k13.nilaipengetahuan.create', compact('rombel', 'mataPelajaran'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'rombel_id' => 'required|integer|exists:rombels,id',
            'mata_pelajaran_id' => 'required|integer|exists:mata_pelajarans,id',
            'siswa_id' => 'required|array',
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

        // Cek PH terakhir berdasarkan mata pelajaran
        $lastPH = NilaiHarian::where('mata_pelajaran_id', $validated['mata_pelajaran_id'])
            ->max('ph'); // Ambil PH terbesar

        $newPH = $lastPH ? $lastPH + 1 : 1; // Jika ada, tambah 1; jika tidak, mulai dari 1

        foreach ($validated['siswa_id'] as $key => $siswaId) {
            $nilai = NilaiHarian::updateOrCreate(
                [
                    'tahun_pelajaran_id' => $tapel->id,
                    'rombel_id' => $validated['rombel_id'],
                    'mata_pelajaran_id' => $validated['mata_pelajaran_id'],
                    'siswa_id' => $siswaId,
                    'ph' => $newPH, // Set nilai PH
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
            $nilai = NilaiHarian::findOrFail($id);

            // Hapus semua nilai dengan PH yang sama untuk setiap siswa
            NilaiHarian::where('ph', $nilai->ph)
                ->where('rombel_id', $nilai->rombel_id)
                ->where('mata_pelajaran_id', $nilai->mata_pelajaran_id)
                ->delete();

            // Ambil kembali semua nilai yang tersisa, urutkan berdasarkan siswa_id dan PH
            $nilaiTersisa = NilaiHarian::where('rombel_id', $nilai->rombel_id)
                ->where('mata_pelajaran_id', $nilai->mata_pelajaran_id)
                ->orderBy('siswa_id', 'asc')  // Urutkan per siswa
                ->orderBy('ph', 'asc') // Pastikan PH juga diurutkan
                ->get();

            // **Perbaiki PH agar tetap berurutan per siswa**
            $phPerSiswa = []; // Menyimpan PH terbaru per siswa

            foreach ($nilaiTersisa as $data) {
                $siswa_id = $data->siswa_id;

                if (!isset($phPerSiswa[$siswa_id])) {
                    $phPerSiswa[$siswa_id] = 1; // PH mulai dari 1 per siswa
                }

                // Update hanya jika perlu
                if ($data->ph !== $phPerSiswa[$siswa_id]) {
                    $data->update(['ph' => $phPerSiswa[$siswa_id]]);
                }

                $phPerSiswa[$siswa_id]++; // Increment PH untuk siswa ini
            }

            return response()->json([
                'message' => 'Nilai berhasil dihapus dan PH diperbarui!',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat menghapus nilai.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function edit($rombel_id, $mapel_id, $ph)
    {
        $rombel = Rombel::with('siswa_rombel')->findOrFail($rombel_id);
        $mataPelajaran = MataPelajaran::findOrFail($mapel_id);

        $nilaiPH = NilaiHarian::where('rombel_id', $rombel_id)
            ->where('mata_pelajaran_id', $mapel_id)
            ->where('ph', $ph)
            ->get();

        return view('guru.k13.nilaipengetahuan.edit', compact('rombel', 'mataPelajaran', 'nilaiPH', 'ph'));
    }

    public function update(Request $request, $rombel_id, $mata_pelajaran_id, $ph)
    {
        try {
            // Validasi input
            $request->validate([
                'siswa_id' => 'required|array',
                'nilai' => 'required|array',
                'nilai.*' => 'nullable|numeric|min:0|max:100',
            ]);

            // Loop untuk update nilai berdasarkan siswa_id dan PH
            foreach ($request->siswa_id as $index => $siswa_id) {
                NilaiHarian::updateOrCreate(
                    [
                        'siswa_id' => $siswa_id,
                        'ph' => $ph,
                        'mata_pelajaran_id' => $mata_pelajaran_id,
                    ],
                    ['nilai' => $request->nilai[$index]]
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

            // Cek apakah ada nilai yang bisa dikirim
            $nilaiHarian = NilaiHarian::where('rombel_id', $rombel_id)
                ->where('mata_pelajaran_id', $mata_pelajaran_id)
                ->get();

            if ($nilaiHarian->isEmpty()) {
                return response()->json(['message' => 'Tidak ada nilai yang bisa dikirim!'], 400);
            }

            $rombel = Rombel::where('id', $rombel_id)->first();
            $tapel = TahunPelajaran::aktif()->first();

            // Dapatkan KKM dari K13KkmMapel
            $kkm = K13KkmMapel::where('kelas_id', $rombel->kelas->id)
                ->where('mata_pelajaran_id', $mata_pelajaran_id)
                ->value('kkm'); // Ambil nilai langsung

            if (!$kkm) {
                return response()->json(['message' => 'KKM tidak ditemukan!'], 400);
            }

            // Update status NilaiHarian menjadi "terkirim"
            NilaiHarian::where('rombel_id', $rombel_id)
                ->where('mata_pelajaran_id', $mata_pelajaran_id)
                ->update(['status' => 'terkirim']);

            // Masukkan data ke NilaiRaport
            foreach ($nilaiHarian as $nilai) {
                // Pastikan nilai_pengetahuan dan nilai_keterampilan tidak NULL
                $nilaiPengetahuan = $nilai->nilai_pengetahuan ?? 0;
                $nilaiKeterampilan = $nilai->nilai_keterampilan ?? 0;

                NilaiRaport::updateOrCreate(
                    [
                        'tahun_pelajaran_id' => $tapel->id,
                        'pembelajaran_id' => 1,
                        'rombel_id' => $rombel_id,
                    ],
                    [
                        'kkm' => $kkm, // Gunakan KKM yang benar
                        'nilai_pengetahuan' => $nilaiPengetahuan,
                        'predikat_pengetahuan' => $this->hitungPredikat($nilaiPengetahuan),
                        'nilai_keterampilan' => $nilaiKeterampilan,
                        'predikat_keterampilan' => $this->hitungPredikat($nilaiKeterampilan),
                        'nilai_spiritual' => $nilai->nilai_spiritual ?? '1',
                        'nilai_sosial' => $nilai->nilai_sosial ?? '1',
                    ]
                );
            }

            DB::commit(); // Commit jika semua berhasil
            return response()->json(['message' => 'Nilai berhasil dikirim dan dimasukkan ke Nilai Raport!']);
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback jika terjadi kesalahan
            return response()->json(['message' => 'Terjadi kesalahan saat mengirim nilai!', 'error' => $e->getMessage()], 500);
        }
    }

    public function batalKirim($rombel_id, $mata_pelajaran_id)
    {
        try {
            // Kembalikan status nilai agar bisa diedit kembali
            NilaiHarian::where('rombel_id', $rombel_id)
                ->where('mata_pelajaran_id', $mata_pelajaran_id)
                ->update(['status' => null]);

            return response()->json(['message' => 'Pengiriman nilai dibatalkan!']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan saat membatalkan pengiriman nilai!'], 500);
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
