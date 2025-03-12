<?php

namespace App\Http\Controllers\Guru\K13;

use App\Http\Controllers\Controller;
use App\Models\Rombel;
use Illuminate\Http\Request;

class NilaiHarianController extends Controller
{
    public function create($id)
    {
        $rombel = Rombel::findOrfail($id);
        return view('guru.k13.nilaipengetahuan.nilaiharian.index');
    }
}
