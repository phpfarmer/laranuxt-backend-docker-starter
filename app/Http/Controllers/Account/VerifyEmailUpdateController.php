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
        $pendingUpdate = UserEmailUpdate::findOrFail($request->route('id'));
        if ($pendingUpdate) {
            if (!$pendingUpdate->hasVerifiedEmail()) {
                $pendingUpdate->markEmailAsVerified();

                $pendingUpdate->user->email = $pendingUpdate->email;
                $pendingUpdate->user->save();

                $pendingUpdate->user->markEmailAsVerified();
            }
        }

        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(
                config('app.frontend_url') . RouteServiceProvider::HOME . '?verified=1'
            );
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return redirect()->intended(
            config('app.frontend_url') . RouteServiceProvider::HOME . '?verified=1'
        );
    }
}
