<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class K13RencanaBobotPenilaian extends Model
{
    protected $guarded = ['id'];
    public function mata_pelajaran()
    {
        return $this->belongsTo(MataPelajaran::class);
    }

    public function rombel()
    {
        return $this->belongsTo(Rombel::class);
    }

    public function pembelajaran()
    {
        return $this->belongsTo(Pembelajaran::class);
    }
}
