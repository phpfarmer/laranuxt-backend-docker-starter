<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Http\Requests\Account\VerifyEmailUpdateRequest;
use App\Models\UserEmailUpdate;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\RedirectResponse;

class VerifyEmailUpdateController extends Controller
{
    /**
     * Mark the authenticated user's updated email address as verified.
     */
    public function __invoke(VerifyEmailUpdateRequest $request): RedirectResponse
    {
        $pendingUpdate = UserEmailUpdate::find($request->route('id'));
        if ($pendingUpdate) {
            if (!$pendingUpdate->hasVerifiedEmail()) {
                $pendingUpdate->markEmailAsVerified();

                $pendingUpdate->user->email = $pendingUpdate->email;
                $pendingUpdate->user->save();

                $pendingUpdate->user->markEmailAsVerified();
                event(new Verified($request->user()));

                $redirectUrl = config('app.frontend_url') . RouteServiceProvider::HOME . '?success=1&message=' . urlencode('Your email address has been successfully verified.');
                return redirect()->intended($redirectUrl);
            }
        }

        if ($request->user()->hasVerifiedEmail()) {
            $redirectUrl = config('app.frontend_url') . RouteServiceProvider::HOME . '?success=0&message=' . urlencode('Email verification failed or is already verified.');
            return redirect()->intended($redirectUrl);
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        $redirectUrl = config('app.frontend_url') . RouteServiceProvider::HOME . '?success=1&message=' . urlencode('Your email address has been successfully verified.');
        return redirect()->intended($redirectUrl);
    }
}
