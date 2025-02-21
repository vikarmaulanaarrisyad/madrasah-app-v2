<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\NilaiSiswa;

class NilaiSiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'siswa_id' => 1,
                'tahun_pelajaran_id' => 1,
                'mata_pelajaran_id' => 1,
                'kelas_id' => 1,
                'nilai' => 85,
                'cp' => 'Pemahaman konsep dasar cukup baik.',
            ],
            [
                'siswa_id' => 2,
                'tahun_pelajaran_id' => 1,
                'mata_pelajaran_id' => 2,
                'kelas_id' => 1,
                'nilai' => 90,
                'cp' => 'Sangat baik dalam memahami materi.',
            ],
        ];

        foreach ($data as $item) {
            NilaiSiswa::firstOrCreate([
                'siswa_id' => $item['siswa_id'],
                'tahun_pelajaran_id' => $item['tahun_pelajaran_id'],
                'mata_pelajaran_id' => $item['mata_pelajaran_id'],
                'kelas_id' => $item['kelas_id'],
            ], $item);
        }
    }
}
