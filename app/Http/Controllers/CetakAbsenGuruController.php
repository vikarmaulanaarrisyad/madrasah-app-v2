<?php

namespace App\Http\Controllers;

use App\Models\AbsensiGuru;
use App\Models\Guru;
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
        $tahun = now()->year;  // Current year

        // Fetch data for the selected guru and month (same as in filterPresensi)
        $presensi = AbsensiGuru::where('guru_id', $guruId)
            ->whereMonth('tgl_presensi', $bulan)
            ->whereYear('tgl_presensi', $tahun)
            ->get();

        $data = [];
        foreach ($presensi as $item) {
            $tanggal = $item->tgl_presensi;

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

        // Load PDF from Blade view with the filtered data
        $pdf = Pdf::loadView('absen.guru.pdf', compact(
            'data',          // Attendance data
            'namaBulan',     // Month name
            'bulan',         // Pass bulan to view
        ))->setPaper('a4', 'landscape');

        // Stream the PDF (to display in the browser)
        return $pdf->stream("presensi-bulan-$bulan.pdf");
    }
}
