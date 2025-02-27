<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class EventFrontController extends Controller
{
    public function index()
    {
        $eventList = Event::orderBy('tanggal', 'desc')->paginate(6); // 6 item per halaman
        return view('frontend.event.index', compact('eventList'));
    }

    public function detail($slug)
    {
        $eventDetail = Event::where('slug', $slug)->first();
        return view('frontend.event.detail', compact('eventDetail'));
    }
}
