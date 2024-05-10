<?php

use Illuminate\Support\Facades\Route;
use Modules\Wallet\Http\Controllers\TransactionController;
use Modules\Wallet\Http\Controllers\WalletController;

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

Route::prefix('wallets')->name('wallets.')->group(function () {
    /**
     * Deposit
     */
    Route::post('deposit', [WalletController::class, 'deposit'])->name('deposit');

    /**
     * Withdraw
     */
    Route::post('withdraw', [WalletController::class, 'withdraw'])->name('withdraw');

    Route::apiResource(null, WalletController::class)->except(['store', 'update']);
});

Route::prefix('transactions')->name('transactions.')->group(function () {
    Route::put('{transaction}/reference', [TransactionController::class, 'updateReference'])->name('reference');
    Route::put('{transaction}/verify', [TransactionController::class, 'verify'])->name('verify');
    Route::put('{transaction}/reject', [TransactionController::class, 'reject'])->name('reject');

//    Route::get('export/excel', [TransactionExportController::class, 'exportExcel'])->name('export-excel');
});

Route::resource('transactions', \Modules\Wallet\Http\Controllers\TransactionController::class)->only(['index', 'show']);
