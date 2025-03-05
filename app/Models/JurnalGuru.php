<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JurnalGuru extends Model
{
    protected $guarded = ['id'];

    public function tahun_pelajaran()
    {
        return $this->belongsTo(TahunPelajaran::class);
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }

    public function rombel()
    {
        return $this->belongsTo(Rombel::class);
    }

    public function mata_pelajaran()
    {
        return $this->belongsTo(MataPelajaran::class);
    }
}
