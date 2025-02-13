<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    public function scopeAktif(Builder $query): void
    {
        $query->where('status', 'Aktif');
    }
}
