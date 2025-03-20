<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Artikel;
use App\Models\Event;
use App\Models\Menu;
use App\Models\PpdbInfo;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function detail($slug)
    {
        // Cek apakah slug ada di tabel menu
        $menu = Menu::where('menu_url', $slug)->first();

        if (!$menu) {
            abort(404); // Jika tidak ditemukan, tampilkan halaman 404
        }

        // Mapping slug ke tampilan yang sesuai
        $viewMapping = [
            'sejarah' => 'frontend.profile.sejarah',
            'visi-misi' => 'frontend.profile.visi-misi',
            'berita' => 'frontend.artikel.index',
            'agenda' => 'frontend.event.index',
        ];

        // Jika slug cocok dengan mapping, tampilkan halaman sesuai
        if (array_key_exists($slug, $viewMapping)) {
            return view($viewMapping[$slug]);
        }

        // Jika slug tidak ada di mapping, coba cek apakah ini artikel atau event
        $artikelDetail = Artikel::where('slug', $slug)->first();
        $artikelList = Artikel::orderBy('tgl_publish', 'desc')->paginate(10);

        return view('frontend.artikel.index', compact('artikelList'));
        $eventDetail = Event::where('slug', $slug)->first();

        if ($artikelDetail) {
            return view('frontend.artikel.index', compact('artikelDetail'));
        } elseif ($eventDetail) {
            return view('frontend.event.detail', compact('eventDetail'));
        }

        // Jika tidak ditemukan di semua kategori, tampilkan halaman default
        return view('frontend.default', compact('menu'));
    }
}
