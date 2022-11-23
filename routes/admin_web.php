<?php

use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Backend\PageController;
use Illuminate\Support\Facades\Route;

Route::prefix('/admin')->middleware('auth:admin_user')->group(function () {
    Route::controller(PageController::class)->name('admin.')->group(function () {
        Route::get('/', 'index')->name('home');
    });
});

Route::prefix('/admin')->name('admin.')->middleware('auth:admin_user')->group(function () {
    Route::resource('/admin-user', AdminUserController::class);
    Route::get('/admin-user/datatables/ssd',[AdminUserController::class, 'ssd'])->name('getDatatable');
});
