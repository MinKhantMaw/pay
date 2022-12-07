<?php

use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\Frontend\PageController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Auth::routes();

Route::middleware('auth')->controller(PageController::class)->group(function () {
    Route::get('/', 'index')->name('home');
    Route::get('/prfile', 'profile')->name('profile');
    Route::get('/update-password', 'updatePassword')->name('updatePassword');
});

Route::controller(AdminLoginController::class)->group(function () {
    Route::get('/admin/login', 'showLoginForm');
    Route::post('/admin/login', 'login')->name('admin.login');
    Route::post('/admin/logout', 'logout')->name('admin.logout');
});
