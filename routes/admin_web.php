<?php

use App\Http\Controllers\Backend\PageController;
use App\Http\Controllers\Bankend\AdminUserController;
use Illuminate\Support\Facades\Route;

Route::prefix('/admin')->middleware('auth:admin_user')->group(function () {
    Route::controller(PageController::class)->name('admin.')->group(function () {
        Route::get('/', 'index')->name('home');
    });

    Route::resource('admin-user', AdminUserController::class);
});
