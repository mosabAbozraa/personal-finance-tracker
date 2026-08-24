<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\WalletController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum','role:admin'])->group(function(){

});

Route::get('email/verify/{id}/{hash}',[EmailVerificationController::class,'emailVerify'])
    ->middleware(['signed'])
    ->name('verification.verify');

Route::post('email/resend',[EmailVerificationController::class,'emailResend'])
    ->middleware('auth:sanctum')
    ->name('verification.resend');

Route::get('/email/verify', function () {
    return response()->json(['message' => 'Please verify your email link.'], 401);
    })->name('verification.notice');

Route::prefix('Auth/')->group(function(){
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
});

Route::middleware('auth:sanctum')->group(function(){
//************************************ Wallets ******************************************
    Route::prefix('wallet/')->group(function(){
        Route::post('add', [WalletController::class, 'add_wallet']);
        Route::get('getAll', [WalletController::class, 'get_wallets']);
        Route::get('getOne/{id}', [WalletController::class, 'get_one_wallet']);
        Route::patch('update/{id}', [WalletController::class, 'update_wallet']);
        Route::delete('delete/{id}', [WalletController::class, 'delete_wallet']);
    });

//************************************ Categories ****************************************
    Route::prefix('category/')->group(function(){
        Route::post('add', [CategoryController::class, 'add_category']);
        Route::get('getAll', [CategoryController::class, 'get_categories']);
        Route::get('getOne/{id}', [CategoryController::class, 'get_one_category']);
        Route::patch('update/{id}', [CategoryController::class, 'update_category']);
        Route::delete('delete/{id}', [CategoryController::class, 'delete_category']);
    });

//************************************ Transactions ***************************************
    Route::prefix('transaction/')->group(function(){
        Route::post('create',[TransactionController::class,'create_transaction']);
        Route::patch('update/{id}',[TransactionController::class,'update_transaction']);
        Route::delete('delete/{id}',[TransactionController::class, 'delete_transaction']);
    });

});
