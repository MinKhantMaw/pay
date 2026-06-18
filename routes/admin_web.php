<?php

use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\ApprovalController;
use App\Http\Controllers\Admin\AuditLogController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
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
    Route::resource('/admin-user', AdminUserController::class)->middleware('permission:role.manage');
    Route::get('/admin-user/datatables/ssd', [AdminUserController::class, 'ssd'])->name('getDatatable')->middleware('permission:role.manage');

    Route::resource('/roles', RoleController::class)->except(['show'])->middleware('permission:role.manage');
    Route::get('/permissions', [PermissionController::class, 'index'])->name('permissions.index')->middleware('permission:role.view');

    Route::get('/approvals', [ApprovalController::class, 'index'])->name('approvals.index')->middleware('permission:approval.view');
    Route::get('/approvals/{approval}', [ApprovalController::class, 'show'])->name('approvals.show')->middleware('permission:approval.view');
    Route::post('/approvals/{approval}/approve', [ApprovalController::class, 'approve'])->name('approvals.approve')->middleware('permission:wallet.adjust_balance|cashin.approve|cashout.approve|transaction.refund|transaction.reverse');
    Route::post('/approvals/{approval}/reject', [ApprovalController::class, 'reject'])->name('approvals.reject')->middleware('permission:wallet.adjust_balance|cashin.reject|cashout.reject|transaction.refund|transaction.reverse');

    Route::get('/audit-logs', [AuditLogController::class, 'index'])->name('audit-logs.index')->middleware('permission:audit.view');
});

Route::prefix('/admin')->name('user.')->middleware('auth:admin_user')->group(function () {
    Route::resource('/user', UserController::class);
    Route::get('/user/datatables/ssd', [UserController::class, 'ssd'])->name('getDatatable');
});

Route::prefix('/admin')->name('wallet.')->middleware('auth:admin_user')->group(function () {
    Route::get('/wallet', [WalletController::class, 'index'])->name('index')->middleware('permission:wallet.view');
    Route::get('/wallet/datatable/ssd', [WalletController::class, 'ssd'])->name('getDatatable')->middleware('permission:wallet.view');
    Route::get('wallet/add/amount', [WalletController::class, 'addAmount'])->name('addAmount')->middleware('permission:wallet.adjust_balance');
    Route::post('wallet/add/amount/store', [WalletController::class, 'addAmountStore'])->name('addAmountStore')->middleware('permission:wallet.adjust_balance');

    Route::get('wallet/reduce/amount', [WalletController::class, 'reduceAmount'])->name('reduceAmount')->middleware('permission:wallet.adjust_balance');
    Route::post('wallet/reduce/amount/store', [WalletController::class, 'reduceAmountStore'])->name('reduceAmountStore')->middleware('permission:wallet.adjust_balance');
});

Route::prefix('/admin')->name('admin.notifications.')->middleware('auth:admin_user')->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index'])->name('index')->middleware('permission:notification.view');
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('mark-as-read');
});
