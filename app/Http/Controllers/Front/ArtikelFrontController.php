<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Artikel;
use Illuminate\Http\Request;

class ArtikelFrontController extends Controller
{
    public function index()
    {
        $artikelList = Artikel::orderBy('tgl_publish', 'desc')->paginate(10);

        return view('frontend.artikel.index', compact('artikelList'));
    }

    // detail artikel
    public function detail($slug)
    {
        $artikelDetail = Artikel::where('slug', $slug)->first();

        return view('layouts.frontend.detail-news', compact('artikelDetail'));
    }
}
