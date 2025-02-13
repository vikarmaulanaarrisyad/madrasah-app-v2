<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jenis_kelamin()
    {
        return $this->belongsTo(JenisKelamin::class);
    }
}
