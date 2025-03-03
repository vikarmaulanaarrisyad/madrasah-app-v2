<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\AbsensiSiswa;
use App\Models\Guru;
use App\Models\HariLibur;
use App\Models\Rombel;
use App\Models\Siswa;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class PresensiSiswaController extends Controller
{
    public function index()
    {
        return view('guru.presensi.siswa.index');
    }

    public function data(Request $request)
    {
        // Ambil tanggal dari request, jika tidak ada gunakan hari ini
        $tanggal = $request->input('tanggal', date('Y-m-d'));

        // Ambil user yang login (diasumsikan sebagai guru)
        $user = Auth::user();
        $guru = Guru::where('user_id', $user->id)->first();

        // Pastikan guru ditemukan
        if (!$guru) {
            return response()->json(['error' => 'Guru tidak ditemukan'], 404);
        }

        // Ambil rombel berdasarkan wali kelas
        $rombel = Rombel::where('wali_kelas_id', $guru->id)->first();

        // Pastikan rombel ditemukan
        if (!$rombel) {
            return response()->json(['error' => 'Rombel tidak ditemukan untuk guru ini'], 404);
        }

        // Ambil semua siswa dalam rombel
        $siswa = Siswa::whereHas('siswa_rombel', function ($q) use ($rombel) {
            $q->where('rombel_id', $rombel->id);
        })->get();

        // Ambil data presensi berdasarkan siswa dan tanggal
        $presensi = AbsensiSiswa::whereIn('siswa_id', $siswa->pluck('id'))
            ->whereDate('tgl_presensi', $tanggal)
            ->get()
            ->keyBy('siswa_id');

        return datatables($siswa)
            ->addIndexColumn()
            ->addColumn('nama', function ($s) {
                return $s->nama_lengkap; // ✅ Mengambil langsung dari objek siswa
            })
            ->addColumn('aksi', function ($s) use ($presensi, $tanggal) {
                // Ambil status presensi jika ada
                $status = isset($presensi[$s->id]) ? $presensi[$s->id]->status : '';

                // Buat radio button dengan event onchange
                return '
                    <div class="text-center">
                        <label class="mr-2">
                            <input type="radio" class="presensi-radio" name="presensi[' . $s->id . ']" value="H" data-siswa="' . $s->id . '" data-tanggal="' . $tanggal . '" ' . ($status == 'H' ? 'checked' : '') . '> H
                        </label>
                        <label class="mr-2">
                            <input type="radio" class="presensi-radio" name="presensi[' . $s->id . ']" value="I" data-siswa="' . $s->id . '" data-tanggal="' . $tanggal . '" ' . ($status == 'I' ? 'checked' : '') . '> I
                        </label>
                        <label class="mr-2">
                            <input type="radio" class="presensi-radio" name="presensi[' . $s->id . ']" value="S" data-siswa="' . $s->id . '" data-tanggal="' . $tanggal . '" ' . ($status == 'S' ? 'checked' : '') . '> S
                        </label>
                        <label class="mr-2">
                            <input type="radio" class="presensi-radio" name="presensi[' . $s->id . ']" value="A" data-siswa="' . $s->id . '" data-tanggal="' . $tanggal . '" ' . ($status == 'A' ? 'checked' : '') . '> A
                        </label>
                    </div>';
            })

            ->escapeColumns([])
            ->make(true);
    }

    public function simpanPresensi(Request $request)
    {
        $siswa_id = $request->input('siswa_id');
        $status = $request->input('status');
        $tanggal = $request->input('tanggal', date('Y-m-d'));

        // Konversi tanggal ke Carbon
        $carbonTanggal = Carbon::parse($tanggal);

        // Validasi data input
        if (!$siswa_id || !$status) {
            return response()->json(['error' => 'Data tidak lengkap'], 400);
        }

        // Cek apakah siswa_id valid (terdaftar di tabel siswa)
        $siswa = Siswa::find($siswa_id);
        if (!$siswa) {
            return response()->json(['error' => 'Siswa tidak ditemukan'], 404);
        }

        // Cek apakah tanggal yang dipilih adalah hari Minggu
        if ($carbonTanggal->isSunday()) {
            return response()->json([
                'error' => 'Presensi tidak dapat dilakukan pada hari Minggu.'
            ], 400);
        }

        // Cek apakah tanggal termasuk hari libur (dari tabel hari_libur)
        $hariLibur = HariLibur::where('tanggal', $tanggal)->exists();
        if ($hariLibur) {
            return response()->json([
                'error' => 'Hari ini adalah hari libur, presensi tidak diperbolehkan.'
            ], 400);
        }

        // Simpan atau update presensi
        $presensi = AbsensiSiswa::updateOrCreate(
            ['siswa_id' => $siswa_id, 'tgl_presensi' => $tanggal],
            ['status' => $status]
        );

        return response()->json([
            'success' => 'Presensi berhasil diperbarui',
            'data' => $presensi
        ], 201);
    }

    public function count(Request $request)
    {
        $tanggal = $request->input('tanggal');

        $hadir = AbsensiSiswa::where('tgl_presensi', $tanggal)->where('status', 'H')->count();
        $alpa = AbsensiSiswa::where('tgl_presensi', $tanggal)->where('status', 'A')->count();
        $izin = AbsensiSiswa::where('tgl_presensi', $tanggal)->where('status', 'I')->count();
        $sakit = AbsensiSiswa::where('tgl_presensi', $tanggal)->where('status', 'S')->count();

        return response()->json([
            'hadir' => $hadir,
            'alpa' => $alpa,
            'izin' => $izin,
            'sakit' => $sakit
        ]);
    }

    public function cekHariLibur(Request $request)
    {
        $tanggal = $request->tanggal;
        $hariLibur = HariLibur::where('tanggal', $tanggal)->first();

        return response()->json([
            'status' => $hariLibur ? 'libur' : 'bekerja',
            'message' => $hariLibur ? 'Hari ini adalah hari libur: ' . $hariLibur->keterangan : 'Hari ini bukan hari libur',
        ]);
    }
}
