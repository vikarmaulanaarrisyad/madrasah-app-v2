<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NilaiSiswaController extends Controller
{
    public function index()
    {
        return view('nilai.index');
    }
}
