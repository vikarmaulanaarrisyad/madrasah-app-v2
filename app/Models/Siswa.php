<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    protected $guarded = ['id'];

    public function jenis_kelamin()
    {
        return $this->belongsTo(JenisKelamin::class);
    }

    public function agama()
    {
        return $this->belongsTo(Agama::class);
    }

    public function kewarganegaraan()
    {
        return $this->belongsTo(Kewarganegaraan::class);
    }

    public function orangtua()
    {
        return $this->belongsTo(OrangTua::class, 'siswa_id');
    }

    public function scopeAktif(Builder $query): void
    {
        $query->where('status', 'Aktif');
    }

    public function siswa_rombel()
    {
        return $this->belongsToMany(Rombel::class, 'siswa_rombel', 'siswa_id', 'rombel_id')
            ->withPivot('tahun_pelajaran_id', 'status', 'keterangan')
            ->withTimestamps();
    }

    public function nilai_merdeka()
    {
        return $this->belongsTo(MerdekaNilaiAkhir::class, 'siswa_id', 'id');
    }
}
