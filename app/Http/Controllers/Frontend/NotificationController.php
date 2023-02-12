<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $authUser = auth()->guard('web')->user();
        $notifications = $authUser->notifications()->paginate(5);
        return view('frontend.notification', ['notifications' => $notifications]);
    }
}
