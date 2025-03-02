<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class K13KdMapel extends Model
{
    protected $table = 'k13_kd_mapels';
    protected $guarded = ['id'];

    public function matapelajaran()
    {
        return $this->belongsTo(MataPelajaran::class, 'mata_pelajaran_id');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'tingkatan_kelas', 'id');
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class, 'semester', 'id');
    }

    public function semesterText()
    {
        $text = '';

        switch ($this->semester) {
            case '2':
                $text = 'Genap';
                break;

            default:
                $text = 'Ganjil';
                break;
        }

        return $text;
    }
}
