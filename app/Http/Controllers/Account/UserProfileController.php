<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
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
        return response()->json($request->user()->getVisibleProperties(), ResponseAlias::HTTP_OK);
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
            // Add more validation rules as needed
        ]);
        $user->name = $request->input('name');
        // Update other fields as needed

        $user->save();

        if ($user->email !== $request->input('email')) {
            // Generate a unique token for verification
            $token = Str::random(32);

            // Store the user email update in the database
            $userEmailUpdate = UserEmailUpdate::create([
                'user_id' => $user->id,
                'old_email' => $user->email,
                'email' => $request->input('email'),
                'token' => $token,
            ]);

            $userEmailUpdate->sendEmailVerificationNotification();
        }

        return response()->json(['message' => 'Profile updated successfully', 'data' => $user->getVisibleProperties()], ResponseAlias::HTTP_OK);
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

        return response()->json(['message' => __('Your account password updated successfully')], ResponseAlias::HTTP_OK);
    }
}
