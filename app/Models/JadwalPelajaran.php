<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalPelajaran extends Model
{
    protected $guarded = ['id'];

    public function rombel()
    {
        return $this->belongsTo(Rombel::class);
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function jamPelajaran()
    {
        return $this->belongsTo(JamPelajaran::class);
    }

    public function mataPelajaran()
    {
        return $this->belongsTo(MataPelajaran::class);
    }
}
