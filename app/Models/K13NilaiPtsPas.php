<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class K13NilaiPtsPas extends Model
{
    protected $guarded = ['id'];

    public function rombel()
    {
        return $this->belongsTo(Rombel::class);
    }
}
