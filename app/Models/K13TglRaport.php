<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class K13TglRaport extends Model
{
    protected $guarded = ['id'];
    protected $table = 'k13_tgl_raports';

    public function tahun_pelajaran()
    {
        return $this->belongsTo(TahunPelajaran::class);
    }
}
