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
    Route::post('/update-password', 'updatePasswordStore')->name('updatePasswordStore');
    Route::get('/wallet', 'wallet')->name('wallet');

    Route::get('/transfers', 'transfer')->name('transfer');
    Route::get('/transfers/confirm', 'transferConfirm')->name('transferConfirm');
    Route::get('/to-account-verify', 'toAccountVerify')->name('toAccountVerify');
    Route::post('/transfers/complete', 'transferComplete')->name('transferComplete');
    Route::get('/transfers/confirm/password-check', 'passwordCheck')->name('passwordCheck');
    Route::get('/transfer-hash', 'transferHash')->name('transferHash');

    Route::get('transactions', 'transactions')->name('transactions');
    Route::get('transactions/{trx_id}', 'transactionsDetails')->name('transactionsDetails');

    Route::get('receive-qr', 'receiveQR')->name('receive-qr');
    Route::get('scan-pay', 'scanPay')->name('scanpay');
});

Route::controller(AdminLoginController::class)->group(function () {
    Route::get('/admin/login', 'showLoginForm');
    Route::post('/admin/login', 'login')->name('admin.login');
    Route::post('/admin/logout', 'logout')->name('admin.logout');
});
