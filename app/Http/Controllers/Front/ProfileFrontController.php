<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileFrontController extends Controller
{
    public function sejarahIndex()
    {
        return view('layouts.frontend.profile.sejarah');
    }
}
