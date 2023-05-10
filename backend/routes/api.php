<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\LoanController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('login', [AuthController::class, 'login'])->name('login');

Route::middleware('auth:sanctum')->group(function() {
    Route::prefix('loans')->group(function () {
        Route::get('', [LoanController::class, 'index']);
        Route::get('{loan}', [LoanController::class, 'show']);
        Route::post('', [LoanController::class, 'store']);
        Route::post('/{loan}/pay', [LoanController::class, 'pay']);
    });
});
