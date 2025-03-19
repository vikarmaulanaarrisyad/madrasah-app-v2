<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class K13NilaiPengetahuan extends Model
{
    protected $guarded = ['id'];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }
}
