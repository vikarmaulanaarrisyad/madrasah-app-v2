<?php

namespace App\Http\Controllers;

use App\Models\AbsensiGuru;
use App\Models\Guru;
use App\Models\JamKerja;
use App\Models\Libur;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CetakAbsenGuruController extends Controller
{
    public function index()
    {
        $gurus = Guru::all();
        return view('absen.guru.index', compact('gurus'));
    }

    public function filterPresensi(Request $request)
    {

        $guruId = $request->guru;
        $bulan = (int) $request->bulan;
        $tahun = now()->year;  // Current year

        // Fetch data for the selected guru and month
        $presensi = AbsensiGuru::where('guru_id', $guruId)
            ->whereMonth('tgl_presensi', $bulan)
            ->whereYear('tgl_presensi', $tahun)
            ->get();

        $data = [];
        foreach ($presensi as $item) {
            $tanggal = $item->tgl_presensi;  // Ensure date is formatted as YYYY-MM-DD

            // Use valid date format as the key for each entry
            $data[$item->guru->nama_lengkap][$tanggal] = [
                'waktu_masuk' => $item->waktu_masuk,
                'waktu_keluar' => $item->waktu_keluar,
                'status' => $item->status,
                'jam_masuk' => $item->jam_masuk,
                'jam_keluar' => $item->jam_keluar,
                'is_holiday' => $item->is_holiday,
            ];
        }

        // Konversi angka bulan ke nama bulan dalam bahasa Indonesia
        $namaBulan = Carbon::create()->month($bulan)->translatedFormat('F');
        return response()->json([
            'data' => $data,
            'namaBulan' => $namaBulan,
            'bulan' => $bulan,
            'jumlahHari' => now()->daysInMonth,
        ]);
    }

    public function downloadPdf(Request $request)
    {
        $guruId = $request->guru;
        $bulan = (int) $request->bulan;
        $tahun = now()->year;

        // Ambil data presensi
        $presensi = AbsensiGuru::with('guru')
            ->where('guru_id', $guruId)
            ->whereMonth('tgl_presensi', $bulan)
            ->whereYear('tgl_presensi', $tahun)
            ->orderBy('tgl_presensi', 'ASC')
            ->get();

        // Ambil jam kerja dan hari libur
        $jamKerjaList = JamKerja::pluck('jam_masuk', 'hari')->toArray();
        $jamKerjaPulangList = JamKerja::pluck('jam_keluar', 'hari')->toArray();

        // Ambil daftar libur dengan tanggal sebagai key dan deskripsi libur sebagai value
        $tanggalLibur = Libur::pluck('description', 'tanggal')->toArray();

        // Tambahkan jam kerja dan cek libur
        foreach ($presensi as $item) {
            $hari = Carbon::parse($item->tgl_presensi)->translatedFormat('l');
            $tanggal = $item->tgl_presensi;

            // Tambahkan jam kerja sesuai hari
            $item->jam_kerja_masuk = $jamKerjaList[$hari] ?? '-';
            $item->jam_kerja_pulang = $jamKerjaPulangList[$hari] ?? '-';

            // Cek jika tanggal termasuk hari libur dan tambahkan deskripsi libur
            if (isset($tanggalLibur[$tanggal])) {
                $item->is_holiday = '1';
                $item->description = $tanggalLibur[$tanggal]; // Menyimpan deskripsi libur
            } else {
                $item->is_holiday = '0';
                $item->description = '-';
            }
        }

        // Format data untuk PDF
        $data = $presensi->groupBy('guru.nama_lengkap')->map(function ($items) {
            return $items->keyBy('tgl_presensi')->map(function ($item) {
                return [
                    'tgl_presensi' => $item->tgl_presensi,
                    'waktu_masuk' => $item->waktu_masuk,
                    'waktu_keluar' => $item->waktu_keluar,
                    'status' => $item->status,
                    'jam_kerja_masuk' => $item->jam_kerja_masuk,
                    'jam_kerja_pulang' => $item->jam_kerja_pulang,
                    'is_holiday' => $item->is_holiday,
                    'description' => $item->description, // Tambahkan deskripsi libur
                ];
            });
        });

        // Konversi bulan ke nama Indonesia
        $namaBulan = Carbon::create()->month($bulan)->translatedFormat('F');

        // Generate PDF
        $pdf = Pdf::loadView('absen.guru.pdf', compact('data', 'namaBulan'))
            ->setPaper('a4', 'landscape');

        return $pdf->stream("presensi-{$guruId}-{$bulan}-{$tahun}.pdf");
    }
}
