<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;

class PageController extends Controller
{
    public function index()
    {
        return view('frontend.home');
    }

    public function profile()
    {
        return view('frontend.profile');
    }
}
