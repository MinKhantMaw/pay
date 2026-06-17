<?php

use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Backend\PageController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Wallet\WalletController;
use Illuminate\Support\Facades\Route;

Route::prefix('/admin')->middleware('auth:admin_user')->group(function () {
    Route::controller(PageController::class)->name('admin.')->group(function () {
        Route::get('/', 'index')->name('home');
    });
});

Route::prefix('/admin')->name('admin.')->middleware('auth:admin_user')->group(function () {
    Route::resource('/admin-user', AdminUserController::class);
    Route::get('/admin-user/datatables/ssd', [AdminUserController::class, 'ssd'])->name('getDatatable');
});

Route::prefix('/admin')->name('user.')->middleware('auth:admin_user')->group(function () {
    Route::resource('/user', UserController::class);
    Route::get('/user/datatables/ssd', [UserController::class, 'ssd'])->name('getDatatable');
});

Route::prefix('/admin')->name('wallet.')->middleware('auth:admin_user')->group(function () {
    Route::get('/wallet', [WalletController::class, 'index'])->name('index');
    Route::get('/wallet/datatable/ssd', [WalletController::class, 'ssd'])->name('getDatatable');
    Route::get('wallet/add/amount', [WalletController::class, 'addAmount'])->name('addAmount');
    Route::post('wallet/add/amount/store', [WalletController::class, 'addAmountStore'])->name('addAmountStore');

    Route::get('wallet/reduce/amount', [WalletController::class, 'reduceAmount'])->name('reduceAmount');
    Route::post('wallet/reduce/amount/store', [WalletController::class, 'reduceAmountStore'])->name('reduceAmountStore');
});

Route::prefix('/admin')->name('admin.notifications.')->middleware('auth:admin_user')->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index'])->name('index');
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('mark-as-read');
});
