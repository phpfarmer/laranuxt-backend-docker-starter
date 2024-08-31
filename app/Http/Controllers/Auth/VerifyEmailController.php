<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\EmailVerificationRequest;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class VerifyEmailController extends Controller
{
    /**
     * Mark the user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        if (!$request->hasValidSignature()) {
            return redirect()->to(config('app.frontend_url') . RouteServiceProvider::LOGIN . '?status=error&message=Invalid or expired signature.');
        }

        $userId = $request->route('id');
        $user = User::find($userId);

        if (!$user) {
            return redirect()->to(config('app.frontend_url') . RouteServiceProvider::LOGIN . '?status=error&message=User not found.');
        }

        $hash = sha1($user->getEmailForVerification());
        if (!hash_equals($hash, (string)$request->route('hash'))) {
            return redirect()->to(config('app.frontend_url') . RouteServiceProvider::LOGIN . '?status=error&message=Invalid or expired signature.');
        }

        Auth::login($user);

        $email = $user->email;
        if ($request->user()->hasVerifiedEmail()) {
            Auth::logout();
            return redirect()->to(config('app.frontend_url') . RouteServiceProvider::LOGIN . '?status=success&email='. $email .'&message=Your email is already verified.');
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        Auth::logout();
        return redirect()->to(config('app.frontend_url') . RouteServiceProvider::LOGIN . '?status=success&email='. $email .'&message=Your email has been successfully verified.');
    }
}
