<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MataPelajaran extends Model
{
    protected $guarded = ['id'];

    public function kurikulum()
    {
        return $this->belongsTo(Kurikulum::class);
    }

    public function parent()
    {
        return $this->hasMany(MataPelajaran::class, 'id', 'parent_id');
    }
}
