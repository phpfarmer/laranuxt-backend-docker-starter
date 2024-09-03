<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserEmailUpdate;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class UserProfileController extends Controller
{
    /**
     * Handle an incoming profile information get request.
     *
     */
    public function show(Request $request): JsonResponse
    {
        $user = $request->user();
        $responseData = $user->getVisibleProperties();

        if ($emailUpdate = $user->emailUpdate()->first()) {
            $responseData['email_update'] = $emailUpdate->getVisibleProperties();
        }

        return response()->json($responseData, ResponseAlias::HTTP_OK);
    }

    /**
     * Handle an incoming profile update request.
     *
     */
    public function store(Request $request): JsonResponse
    {
        $user = auth()->user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
        ]);
        $user->name = $request->input('name');

        $user->save();

        if ($user->email !== $request->input('email')) {
            $token = Str::random(32);

            $userEmailUpdate = UserEmailUpdate::create([
                'user_id' => $user->id,
                'old_email' => $user->email,
                'email' => $request->input('email'),
                'token' => $token,
            ]);

            $userEmailUpdate->sendEmailVerificationNotification();
        }

        $message = $user->email !== $request->input('email')
            ? 'Profile updated successfully. Please check your email to verify your new address.'
            : 'Profile updated successfully.';

        $responseData = $user->getVisibleProperties();

        if ($emailUpdate = $user->emailUpdate()->first()) {
            $responseData['email_update'] = $emailUpdate->getVisibleProperties();
        }

        return response()->json(['message' => $message, 'data' => $responseData], ResponseAlias::HTTP_OK);
    }

    /**
     * Handle an incoming profile delete request.
     *
     */
    public function destroy(Request $request): JsonResponse
    {
        $user = auth()->user();

        $request->validate([
            'password' => ['required', Rules\Password::defaults()],
        ]);

        #Match The Old Password
        if (!Hash::check($request->password, auth()->user()->password)) {
            throw ValidationException::withMessages([
                'current_password' => [__("Current password doesn't match!")],
            ]);
        }

        $user->delete();

        return response()->json(['message' => __('Your account has been deleted successfully')], ResponseAlias::HTTP_OK);
    }
}
