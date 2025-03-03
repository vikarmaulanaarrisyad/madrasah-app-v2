<?php

namespace App\Http\Controllers;

use App\Models\AbsensiSiswa;
use App\Models\HariLibur;
use App\Models\Rombel;
use App\Models\Siswa;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CetakAbsenSiswaController extends Controller
{
    public function index()
    {
        $rombels = Rombel::with('kelas')->get();
        return view('admin.absen.siswa.index', compact('rombels'));
    }

    public function filterPresensi(Request $request)
    {
        $bulan = (int) $request->bulan;
        $rombelId = $request->rombel;

        if (!$bulan) {
            return response()->json(['message' => 'Bulan tidak boleh kosong'], 400);
        }

        if (!$rombelId) {
            return response()->json(['message' => 'Rombel tidak boleh kosong'], 400);
        }

        // Konversi angka bulan ke nama bulan dalam bahasa Indonesia
        $namaBulan = Carbon::create()->month($bulan)->translatedFormat('F');

        // Ambil jumlah hari dalam bulan yang dipilih
        $jumlahHari = Carbon::create()->month($bulan)->daysInMonth;

        // Ambil data presensi berdasarkan rombel dan bulan yang dipilih
        $presensi = AbsensiSiswa::whereMonth('tgl_presensi', $bulan)
            ->whereHas('siswa.siswa_rombel', function ($query) use ($rombelId) {
                $query->where('siswa_rombel.rombel_id', $rombelId);
            })
            ->get();

        // Array to store attendance data
        $dataPresensi = [];

        // Iterate through each attendance record
        foreach ($presensi as $attendance) {
            $siswa = $attendance->siswa; // Get the related siswa
            $tanggal = Carbon::parse($attendance->tgl_presensi)->toDateString(); // Get the attendance date

            // Initialize the student's entry in the array if not already set
            if (!isset($dataPresensi[$siswa->nama_lengkap])) {
                $dataPresensi[$siswa->nama_lengkap] = [];
            }

            // Set the attendance status for the student on the given date
            $dataPresensi[$siswa->nama_lengkap][$tanggal] = $attendance->status;
        }

        // If needed, iterate through each student and fill in missing dates with '-'
        foreach ($dataPresensi as $nama => $attendances) {
            for ($i = 1; $i <= $jumlahHari; $i++) {
                $tanggal = Carbon::create(null, $bulan, $i)->toDateString();

                // If no attendance found for that date, set status to '-'
                if (!isset($attendances[$tanggal])) {
                    $dataPresensi[$nama][$tanggal] = '-';
                }
            }
        }

        // Return the data as JSON
        return response()->json([
            'namaBulan' => $namaBulan,
            'count' => $jumlahHari,
            'data' => $dataPresensi
        ]);
    }

    public function downloadPdf(Request $request)
    {
        $bulan = (int) $request->bulan;
        $rombelId = $request->rombel;

        if (!$bulan) {
            return response()->json(['message' => 'Bulan tidak boleh kosong'], 400);
        }

        if (!$rombelId) {
            return response()->json(['message' => 'Rombel tidak boleh kosong'], 400);
        }

        $rombel = Rombel::with('tahun_pelajaran.semester', 'walikelas')->where('id', $rombelId)->first();

        // Konversi angka bulan ke nama bulan dalam bahasa Indonesia
        $namaBulan = Carbon::create()->month($bulan)->translatedFormat('F');

        // Ambil jumlah hari dalam bulan yang dipilih
        $jumlahHari = Carbon::create()->month($bulan)->daysInMonth;

        // Ambil data presensi berdasarkan rombel dan bulan yang dipilih
        $presensi = AbsensiSiswa::whereMonth('tgl_presensi', $bulan)
            ->whereHas('siswa.siswa_rombel', function ($query) use ($rombelId) {
                $query->where('siswa_rombel.rombel_id', $rombelId);
            })
            ->get();

        // Array to store attendance data
        $dataPresensi = [];

        // Iterate through each attendance record
        foreach ($presensi as $attendance) {
            $siswa = $attendance->siswa; // Get the related siswa
            $tanggal = Carbon::parse($attendance->tgl_presensi)->toDateString(); // Get the attendance date

            // Initialize the student's entry in the array if not already set
            if (!isset($dataPresensi[$siswa->nisn])) {
                $dataPresensi[$siswa->nisn] = [
                    'nis' => $siswa->nis, // Add nis
                    'nisn' => $siswa->nisn, // Add nisn
                    'nama' => $siswa->nama_lengkap, // Add nama
                    'kehadiran' => [], // Initialize kehadiran for the student
                ];
            }

            // Set the attendance status for the student on the given date
            $dataPresensi[$siswa->nisn]['kehadiran'][$tanggal] = $attendance->status;
        }

        // If needed, iterate through each student and fill in missing dates with '-'
        foreach ($dataPresensi as $nisn => $attendances) {
            for ($i = 1; $i <= $jumlahHari; $i++) {
                $tanggal = Carbon::create(null, $bulan, $i)->toDateString();

                // If no attendance found for that date, set status to '-'
                if (!isset($attendances['kehadiran'][$tanggal])) {
                    $dataPresensi[$nisn]['kehadiran'][$tanggal] = 'A';
                }
            }
        }

        $hariLiburArr = HariLibur::whereMonth('tanggal', $bulan)
            ->pluck('tanggal')
            ->toArray();


        // Load PDF from Blade view with required data
        $pdf = Pdf::loadView('admin.absen.siswa.pdf', compact(
            'dataPresensi', // Attendance data
            'namaBulan',    // Month name
            'jumlahHari',   // Number of days in the month
            'bulan',         // Pass bulan to view
            'rombel',
            'hariLiburArr'
        ))->setPaper('a4', 'landscape');

        // Stream the PDF (to display in the browser)
        return $pdf->stream("presensi-bulan-$bulan.pdf");
    }

    public function cekHariLibur()
    {
        $hariLibur = HariLibur::all(); // Ambil semua tanggal libur

        return response()->json($hariLibur);
    }
}
