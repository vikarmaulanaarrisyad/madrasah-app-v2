<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sekolah extends Model
{
    protected $guarded = ['id'];
    public function guru()
    {
        return $this->belongsTo(Guru::class, 'kepala_madrasah_id');
    }
}
