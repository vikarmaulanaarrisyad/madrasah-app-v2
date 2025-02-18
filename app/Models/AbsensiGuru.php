<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AbsensiGuru extends Model
{
    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }
}
