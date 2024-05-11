<?php

use Illuminate\Support\Facades\Route;
use Modules\Comment\Http\Controllers\CommentController;

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

Route::prefix('comment-types/{type}')->group(function () {
    Route::apiResource('comments', CommentController::class);
    Route::put('comments/{comment}/approve-toggle', [CommentController::class, 'approveToggle']);
});
