<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JurnalGuru;

class JurnalGuruSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'guru_id' => 1,
                'mata_pelajaran_id' => 1,
                'kelas_id' => 1,
                'tanggal' => now()->toDateString(),
                'materi' => 'Pengenalan dasar-dasar pemrograman',
            ],
            [
                'guru_id' => 2,
                'mata_pelajaran_id' => 2,
                'kelas_id' => 1,
                'tanggal' => now()->toDateString(),
                'materi' => 'Materi tentang konsep bilangan dalam matematika',
            ],
        ];

        foreach ($data as $item) {
            JurnalGuru::firstOrCreate([
                'guru_id' => $item['guru_id'],
                'mata_pelajaran_id' => $item['mata_pelajaran_id'],
                'kelas_id' => $item['kelas_id'],
                'tanggal' => $item['tanggal'],
            ], $item);
        }
    }
}
