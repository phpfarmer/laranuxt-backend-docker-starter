<?php

use App\Http\Controllers\Account\PasswordChangeController;
use App\Http\Controllers\Account\UserProfileController;
use Illuminate\Support\Facades\Route;

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

Route::prefix('account')->middleware(['auth:sanctum'])->group(function () {
    Route::delete('/profile/delete', [UserProfileController::class, 'destroy']);
    Route::get('/profile', [UserProfileController::class, 'show']);
    Route::post('/profile', [UserProfileController::class, 'store']);
    Route::post('/password-change', PasswordChangeController::class);
});

