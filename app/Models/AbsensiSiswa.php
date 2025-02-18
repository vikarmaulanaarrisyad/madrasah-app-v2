<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AbsensiSiswa extends Model
{
    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }
}
