<?php

namespace App\Imports;

use App\Models\Agama;
use App\Models\JenisKelamin;
use App\Models\OrangTua;
use App\Models\Siswa;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;


class SiswaImport implements ToModel, WithHeadingRow
{

    public function model(array $row)
    {
        if (empty($row['nisn']) || empty($row['nama_lengkap']) || empty($row['tanggal_lahir_yyyy_mm_dd']) || empty($row['agama']) || empty($row['jenis_kelamin'])) {
            return null;
        }

        DB::beginTransaction();
        try {
            $this->prosesDataSiswa($row);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    /**
     * Proses data siswa: Insert atau Update
     */
    private function prosesDataSiswa(array $row)
    {
        $siswa = Siswa::updateOrCreate(
            ['nisn' =>  $row['nisn']],
            [
                'nama_lengkap' => $row['nama_lengkap'] ?? '',
                'nama_panggilan' => $row['nama_panggilan'] ?? '',
                'nis' => $row['nis'] ?? 0,
                'nik' => $row['nik'] ?? 0,
                'kk' => $row['kk'] ?? 0,
                'jenis_kelamin_id' => $this->getJenisKelaminId($row['jenis_kelamin'] ?? ''),
                'tempat_lahir' => $row['tempat_lahir'] ?? '',
                'tgl_lahir' => $this->convertExcelDateToDate($row['tanggal_lahir_yyyy_mm_dd']),
                'agama_id' => $this->getAgamaId($row['agama'] ?? ''),
                'jumlah_saudara' => $row['jumlah_saudara'] ?? 0,
                'anak_ke' => $row['anak_ke'] ?? 0,
                'alamat' => $row['alamat'] ?? '-',
                'kelas' => $row['kelas'] ?? 1,
                'kewarganegaraan_id' => 1,
            ]
        );

        $this->prosesDataOrangTua($siswa->id, $row);
    }


    /**
     * Proses data orang tua atau wali
     */
    private function prosesDataOrangTua($siswaId, array $row)
    {
        OrangTua::updateOrCreate(
            ['siswa_id' => $siswaId],
            [
                'nama_ayah' => $row['nama_ayah'] ?? '',
                'nama_ibu' => $row['nama_ibu'] ?? '',
                'nama_walimurid' => $row['wali_murid'] ?? '',
                'pendidikan_ayah_id' => $row['pendidikan_ayah'] ?? 0,
                'pendidikan_ibu_id' => $row['pendidikan_ibu'] ?? 0,
                'pendidikan_walimurid_id' => $row['pendidikan_wali'] ?? 0,
                'pekerjaan_ayah_id' => $row['pekerjaan_ayah'] ?? 0,
                'pekerjaan_ibu_id' => $row['pekerjaan_ibu'] ?? 0,
                'pekerjaan_walimurid_id' => $row['pekerjaan_wali'] ?? 0,
                'hubungan_siswa_wali' => $row['hubungan_siswa_wali'] ?? '',
            ]
        );
    }

    /**
     * Mendapatkan ID Jenis Kelamin
     */
    private function getJenisKelaminId($jenisKelamin)
    {
        return strtolower($jenisKelamin) === 'perempuan' ? 2 : 1;
    }

    /**
     * Mendapatkan ID Agama
     */
    private function getAgamaId($agama)
    {
        return strtolower($agama) === 'islam' ? 1 : 2;
    }

    /**
     * Konversi format tanggal dari Excel ke Y-m-d
     */
    private function convertExcelDateToDate($excelDate)
    {
        if (!$excelDate || !is_numeric($excelDate)) {
            return null; // Jika kosong atau bukan angka, return null
        }

        return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($excelDate)->format('Y-m-d');
    }
}
