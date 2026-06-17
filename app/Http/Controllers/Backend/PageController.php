<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;

class PageController extends Controller
{
    public function index(DashboardService $dashboardService)
    {
        return view('backend.home', $dashboardService->data());
    }
}
