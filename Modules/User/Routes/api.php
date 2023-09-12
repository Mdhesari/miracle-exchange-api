<?php

use Illuminate\Support\Facades\Route;
use Modules\User\Controllers\UserAuthrizationController;
use Modules\User\Http\Controllers\AdminUserAuthorizationController;
use Modules\User\Http\Controllers\ProfileController;
use Modules\User\Http\Controllers\UserAuthorizationController;
use Modules\User\Http\Controllers\UserController;

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

Route::prefix('users')->name('users.')->group(function () {
    Route::put('{user}/restore', [UserController::class, 'restore'])->name('restore');
    Route::post('{user}/authorization', UserAuthorizationController::class)->name('authorize');
    Route::put('{user}/authorization', AdminUserAuthorizationController::class)->name('authorize');
});

Route::apiResource('users', UserController::class);

Route::get('profile', [ProfileController::class, 'index'])->name('profile.index');
