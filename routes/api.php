<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\WalletController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum','role:admin'])->group(function(){

});

Route::middleware('auth:sanctum')->group(function(){

    //************************************ Wallets ***************************************
    Route::prefix('wallet')->group(function(){
        Route::post('/add', [WalletController::class, 'add_wallet']);
        Route::get('/getAll', [WalletController::class, 'get_wallets']);
        Route::get('/getOne/{id}', [WalletController::class, 'get_one_wallet']);
        Route::put('/update/{id}', [WalletController::class, 'update_wallet']);
        Route::delete('/delete/{id}', [WalletController::class, 'delete_wallet']);
    });

    //************************************ Categories ***************************************
    Route::prefix('category')->group(function(){
        Route::post('/add', [CategoryController::class, 'add_category']);
        Route::get('/getAll', [CategoryController::class, 'get_categories']);
        Route::get('/getOne/{id}', [CategoryController::class, 'get_one_category']);
        Route::put('/update/{id}', [CategoryController::class, 'update_category']);
        Route::delete('/delete/{id}', [CategoryController::class, 'delete_category']);
    });
});

Route::prefix('Auth')->group(function(){
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout']);
});
