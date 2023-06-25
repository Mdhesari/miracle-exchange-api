<?php

use Illuminate\Support\Facades\Route;
use Modules\Helpdesk\Http\Controllers\TicketController;
use Modules\Helpdesk\Http\Controllers\TicketMessageController;

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

Route::apiResource('tickets', TicketController::class);

Route::prefix('tickets')->name('tickets.')->group(function () {
    Route::get('my-tickets', [TicketController::class, 'getMyTickets'])->name('my-tickets');

    Route::prefix('{ticket}')->group(function () {
        /*
         * Messages
         */
        Route::apiResource('messages', TicketMessageController::class);

    });
});
