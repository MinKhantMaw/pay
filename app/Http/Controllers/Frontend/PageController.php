<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;

class PageController extends Controller
{
    public function index()
    {
        return view('auth.admin_login');
    }
}
