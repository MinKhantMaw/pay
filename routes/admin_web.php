<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Backend\PageController;
use App\Http\Controllers\Admin\AdminUserController;

Route::prefix('/admin')->middleware('auth:admin_user')->group(function () {
    Route::controller(PageController::class)->name('admin.')->group(function () {
        Route::get('/', 'index')->name('home');
    });
});

Route::prefix('/admin')->name('admin.')->middleware('auth:admin_user')->group(function () {
    Route::resource('/admin-user', AdminUserController::class);
    Route::get('/admin-user/datatables/ssd',[AdminUserController::class, 'ssd'])->name('getDatatable');
});

Route::prefix('/admin')->name('user.')->middleware('auth:admin_user')->group(function () {
    Route::resource('/user', UserController::class);
    Route::get('/user/datatables/ssd',[UserController::class, 'ssd'])->name('getDatatable');
});
