<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*
 * Postman
 */


use Illuminate\Support\Facades\Route;
use Modules\Postman\Http\Controllers\PostmanController;

Route::get('postman', [PostmanController::class, 'download'])->name('download-postman-collection');
