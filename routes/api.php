<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(AuthController::class)->group(function () {
    Route::post('register', 'register');
    Route::post('login', 'login');

    Route::middleware(['auth:api'])->group(function () {
        Route::get('profile', 'profile');
        Route::post('logout', 'logout');
        Route::get('transaction', 'transaction');
        Route::get('transaction/{id}', 'transactionDetail');

        Route::get('notification', 'notification');
        Route::get('notification/{id}', 'notificationDetail');

        Route::get('to-account-verify', 'toAccountVerify');
        Route::get('transfer/confirm', 'transferConfirm');
        Route::post('transfer/complete', 'transferComplete');

        Route::get('scan-and-pay-form', 'scanAndPayForm');
        Route::get('/scan-and-pay/confirm', 'scanAndPayConfirm');
        Route::post('/scan-and-pay/complete', 'scanAndPayComplete');
    });
});
