<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\SendEmailVerificationRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     */
    public function store(SendEmailVerificationRequest $request): JsonResponse|RedirectResponse
    {
        $user = User::find($request->id);

        $user->sendEmailVerificationNotification();

        return response()->json(['message' => 'The email verification link has been sent successfully!']);
    }
}
