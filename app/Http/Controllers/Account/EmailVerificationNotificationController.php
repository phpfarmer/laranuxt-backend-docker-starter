<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class EmailVerificationNotificationController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $user = auth()->user();
        $emailUpdate = $user->emailUpdate;

        if ($emailUpdate) {
            $emailUpdate->sendEmailVerificationNotification();

            return response()->json([
                'message' => 'A new verification email has been sent.'
            ], ResponseAlias::HTTP_OK);
        }

        return response()->json([
            'message' => 'No pending email update found, or the email has already been verified.'
        ], ResponseAlias::HTTP_NOT_FOUND);
    }

    public function destroy(Request $request): JsonResponse
    {
        $user = auth()->user();
        $emailUpdate = $user->emailUpdate;

        if ($emailUpdate) {
            $emailUpdate->delete();

            return response()->json([
                'message' => 'The email address change has been deleted.'
            ], ResponseAlias::HTTP_OK);
        }

        return response()->json([
            'message' => 'No pending email update found, or the email has already been verified.'
        ], ResponseAlias::HTTP_NOT_FOUND);
    }
}
