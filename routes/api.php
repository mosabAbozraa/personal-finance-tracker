<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\WalletController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum','role:admin'])->group(function(){

});

Route::prefix('auth')->group(function(){
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
});



Route::middleware('auth:sanctum')->group(function(){
//************************************ Wallets ******************************************
    Route::controller(TransactionController::class)->prefix('wallet')->group(function(){
        Route::post('add', 'add_wallet');
        Route::get('getAll', 'get_wallets');
        Route::get('getOne/{id}', 'get_one_wallet');
        Route::patch('update/{id}', 'update_wallet');
        Route::delete('delete/{id}', 'delete_wallet');
    });

//************************************ Categories ****************************************
    Route::controller(TransactionController::class)->prefix('category')->group(function(){
        Route::post('add', 'add_category');
        Route::get('getAll', 'get_categories');
        Route::get('getOne/{id}', 'get_one_category');
        Route::patch('update/{id}', 'update_category');
        Route::delete('delete/{id}', 'delete_category');
    });

//************************************ Transactions ***************************************
    Route::controller(TransactionController::class)->prefix('transaction')->group(function(){
        Route::post('create', 'create_transaction');
        Route::patch('update/{id}', 'update_transaction');
        Route::delete('delete/{id}', 'delete_transaction');
    });

//**************************************** Budgets*****************************************
    Route::controller(BudgetController::class)->prefix('budget')->group(function(){
        Route::post('add/{categoryId}', 'addBudget');
        Route::patch('update/{budgetId}', 'updateBudget');
        Route::delete('delete/{budgetId}', 'removeBudget');
    });

});
