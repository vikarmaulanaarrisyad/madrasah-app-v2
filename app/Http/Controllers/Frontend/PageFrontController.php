<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;

class PageFrontController extends Controller
{
    // Menampilkan halaman berdasarkan slug
    public function show($slug)
    {
        $page = Page::where('slug', $slug)->firstOrFail();

        return view('frontend.pages.show', compact('page'));
    }
}
