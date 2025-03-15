<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\PpdbInfo;
use Illuminate\Http\Request;

class PpdbFrontController extends Controller
{
    public function index($slug)
    {
        $ppdb = PpdbInfo::where('slug', $slug)->first();
        return view('frontend.ppdb.index', compact('ppdb'));
    }
}
