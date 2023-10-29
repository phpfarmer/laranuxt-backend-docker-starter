<?php

use App\Http\Controllers\Account\VerifyEmailUpdateController;
use Illuminate\Support\Facades\Route;

Route::get('/verify-email-update/{id}/{token}/{hash}', VerifyEmailUpdateController::class)
    ->middleware(['auth', 'signed', 'throttle:6,1'])
    ->name('email_update.verification.verify');
