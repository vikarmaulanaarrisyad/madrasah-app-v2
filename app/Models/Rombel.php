<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rombel extends Model
{
    public function tahun_pelajaran()
    {
        return $this->belongsTo(TahunPelajaran::class);
    }
}
