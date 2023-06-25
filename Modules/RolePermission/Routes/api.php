<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\RolePermission\Http\Controllers\PermissionController;
use Modules\RolePermission\Http\Controllers\RoleController;

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

Route::prefix('roles')->name('roles.')->group(function () {
    Route::post('{role}/assign', [RoleController::class, 'assign'])->name('assign');
    Route::post('{role}/revoke', [RoleController::class, 'revoke'])->name('revoke');
});

Route::apiResource('roles', RoleController::class);

Route::get('permissions', [PermissionController::class, 'index'])->name('permissions.index');
