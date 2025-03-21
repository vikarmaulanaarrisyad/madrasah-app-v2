<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // AplikasiSeeder::class,
            // RolePermissionSeeder::class,
            // AgamaSeeder::class,
            // JenisKelaminSeeder::class,
            // PendidikanSeeder::class,
            // PekerjaanSeeder::class,
            // HobiSeeder::class,
            // CitaCitaSeeder::class,
            // JarakSeeder::class,
            // TinggalSeeder::class,
            // SemesterSeeder::class,
            // TahunPelajaranSeeder::class,
            // KurikulumSeeder::class,
            MataPelajaranSeeder::class,
            // KelasSeeder::class,
            // UserSeeder::class,
            // GuruSeeder::class,
            // RombelSeeder::class,
            // KewarganegaraanSeeder::class,
            // SiswaSeeder::class,
            // JurnalGuruSeeder::class,
            // NilaiSiswaSeeder::class,
            // SekolahSeeder::class,
        ]);
    }
}
