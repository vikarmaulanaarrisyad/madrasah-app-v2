<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MerdekaNilaiFormatif extends Model
{
    protected $guarded = ['id'];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }
}
