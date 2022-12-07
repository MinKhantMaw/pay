<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PageController extends Controller
{
    public function index()
    {
        return view('frontend.home');
    }

    public function profile()
    {
        $user = Auth::guard('web')->user();
        return view('frontend.profile', ['user' => $user]);
    }

    public function updatePassword()
    {
        return view('frontend.update_password');
    }
}
