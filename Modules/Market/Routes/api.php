<?php

use Illuminate\Support\Facades\Route;
use Modules\Market\Http\Controllers\MarketController;
use Modules\Market\Http\Controllers\MarketToggleController;

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

Route::apiResource('markets', MarketController::class);

Route::put('markets/{market}/status-toggle', MarketToggleController::class)->name('markets.status-toggle');
