<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\WalletController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware(['auth:sanctum','role:admin'])->group(function(){

});

Route::middleware('auth:sanctum')->group(function(){
    Route::post('addWallet', [WalletController::class, 'add_wallet']);
    Route::get('getWallets', [WalletController::class, 'get_wallets']);
    Route::get('getOneWallet/{id}', [WalletController::class, 'get_one_wallet']);
    Route::put('updateWallet/{id}', [WalletController::class, 'update_wallet']);
    Route::delete('delete_wallet/{id}', [WalletController::class, 'delete_wallet']);
});

Route::prefix('Auth')->group(function(){
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout']);
});
