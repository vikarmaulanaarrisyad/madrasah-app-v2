<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class K13RencanaNilaiPengetahuan extends Model
{
    public function mata_pelajaran()
    {
        return $this->belongsTo(MataPelajaran::class);
    }
}
