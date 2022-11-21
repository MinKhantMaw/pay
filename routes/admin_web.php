<?php

use App\Http\Controllers\Backend\PageController;
use Illuminate\Support\Facades\Route;

Route::prefix('/admin')->middleware('auth:admin_user')->group(function () {
    Route::controller(PageController::class)->group(function () {
        Route::get('/', 'index');
    });
});
