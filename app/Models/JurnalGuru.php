<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JurnalGuru extends Model
{
    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function mata_pelajaran()
    {
        return $this->belongsTo(MataPelajaran::class);
    }
}
