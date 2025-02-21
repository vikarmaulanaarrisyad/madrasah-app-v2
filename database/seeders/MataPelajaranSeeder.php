<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MataPelajaran;

class MataPelajaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mataPelajaranList = [
            // Kelompok A (Wajib)
            ['kurikulum_id' => 1, 'kode' => 'MP002', 'nama' => 'Pendidikan Pancasila dan Kewarganegaraan', 'kelompok' => 'A', 'parent_id' => null],
            ['kurikulum_id' => 1, 'kode' => 'MP003', 'nama' => 'Bahasa Indonesia', 'kelompok' => 'A', 'parent_id' => null],
            ['kurikulum_id' => 1, 'kode' => 'MP004', 'nama' => 'Matematika', 'kelompok' => 'A', 'parent_id' => null],
            ['kurikulum_id' => 1, 'kode' => 'MP005', 'nama' => 'Ilmu Pengetahuan Alam', 'kelompok' => 'A', 'parent_id' => null],
            ['kurikulum_id' => 1, 'kode' => 'MP006', 'nama' => 'Ilmu Pengetahuan Sosial', 'kelompok' => 'A', 'parent_id' => null],
            ['kurikulum_id' => 1, 'kode' => 'MP007', 'nama' => 'Seni Budaya dan Prakarya', 'kelompok' => 'A', 'parent_id' => null],
            ['kurikulum_id' => 1, 'kode' => 'MP008', 'nama' => 'Pendidikan Jasmani, Olahraga, dan Kesehatan', 'kelompok' => 'A', 'parent_id' => null],
            ['kurikulum_id' => 1, 'kode' => 'MP010', 'nama' => 'Al-Qur\'an Hadits', 'kelompok' => 'A', 'parent_id' => null],
            ['kurikulum_id' => 1, 'kode' => 'MP011', 'nama' => 'Akidah Akhlak', 'kelompok' => 'A', 'parent_id' => null],
            ['kurikulum_id' => 1, 'kode' => 'MP012', 'nama' => 'Fiqih', 'kelompok' => 'A', 'parent_id' => null],
            ['kurikulum_id' => 1, 'kode' => 'MP013', 'nama' => 'Sejarah Kebudayaan Islam', 'kelompok' => 'A', 'parent_id' => null],
            ['kurikulum_id' => 1, 'kode' => 'MP014', 'nama' => 'Bahasa Arab', 'kelompok' => 'A', 'parent_id' => null],

            // Kelompok B (Muatan Lokal dan Keterampilan)
            ['kurikulum_id' => 1, 'kode' => 'MP009', 'nama' => 'Bahasa Daerah (Bahasa Jawa)', 'kelompok' => 'B', 'parent_id' => null],

            // Kelompok A (Wajib) KUMERD
            ['kurikulum_id' => 2, 'kode' => 'MP002', 'nama' => 'Pendidikan Pancasila dan Kewarganegaraan', 'kelompok' => 'A', 'parent_id' => null],
            ['kurikulum_id' => 2, 'kode' => 'MP003', 'nama' => 'Bahasa Indonesia', 'kelompok' => 'A', 'parent_id' => null],
            ['kurikulum_id' => 2, 'kode' => 'MP004', 'nama' => 'Matematika', 'kelompok' => 'A', 'parent_id' => null],
            ['kurikulum_id' => 2, 'kode' => 'MP005', 'nama' => 'Ilmu Pengetahuan Alam Dan Sosial', 'kelompok' => 'A', 'parent_id' => null],
            ['kurikulum_id' => 2, 'kode' => 'MP007', 'nama' => 'Seni Budaya', 'kelompok' => 'A', 'parent_id' => null],
            ['kurikulum_id' => 2, 'kode' => 'MP008', 'nama' => 'Pendidikan Jasmani, Olahraga, dan Kesehatan', 'kelompok' => 'A', 'parent_id' => null],
            ['kurikulum_id' => 2, 'kode' => 'MP010', 'nama' => 'Al-Qur\'an Hadits', 'kelompok' => 'A', 'parent_id' => null],
            ['kurikulum_id' => 2, 'kode' => 'MP011', 'nama' => 'Akidah Akhlak', 'kelompok' => 'A', 'parent_id' => null],
            ['kurikulum_id' => 2, 'kode' => 'MP012', 'nama' => 'Fiqih', 'kelompok' => 'A', 'parent_id' => null],
            ['kurikulum_id' => 2, 'kode' => 'MP013', 'nama' => 'Sejarah Kebudayaan Islam', 'kelompok' => 'A', 'parent_id' => null],
            ['kurikulum_id' => 2, 'kode' => 'MP014', 'nama' => 'Bahasa Arab', 'kelompok' => 'A', 'parent_id' => null],
            ['kurikulum_id' => 2, 'kode' => 'MP014', 'nama' => 'Bahasa Inggris', 'kelompok' => 'A', 'parent_id' => null],

            // Kelompok B (Muatan Lokal dan Keterampilan)
            ['kurikulum_id' => 2, 'kode' => 'MP009', 'nama' => 'Bahasa Daerah (Bahasa Jawa)', 'kelompok' => 'B', 'parent_id' => null],
        ];

        foreach ($mataPelajaranList as $mataPelajaran) {
            MataPelajaran::firstOrCreate([
                'kurikulum_id' => $mataPelajaran['kurikulum_id'],
                'kode' => $mataPelajaran['kode'],
                'nama' => $mataPelajaran['nama'],
                'kelompok' => $mataPelajaran['kelompok'],
                'parent_id' => $mataPelajaran['parent_id']
            ]);
        }
    }
}
