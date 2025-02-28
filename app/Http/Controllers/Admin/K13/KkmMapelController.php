<?php

namespace App\Http\Controllers\Admin\K13;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class KkmMapelController extends Controller
{
    public function index()
    {
        return view('admin.k13.index');
    }
}
