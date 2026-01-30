<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    /**
     * API login: atgriež Sanctum tokenu
     */
    public function store(LoginRequest $request): JsonResponse
    {
        $request->authenticate();

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Ja gribi – vari dzēst vecos tokenus katru login:
        // $user->tokens()->delete();

        $token = $user->createToken('api')->plainTextToken;

        return response()->json([
            'message' => 'Logged in',
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'vards' => $user->vards,
                'uzvards' => $user->uzvards,
                'epasts' => $user->epasts,
                'loma' => $user->loma,
            ],
        ]);
    }

    /**
     * API logout: dzēš tokenu (routei jābūt zem auth:sanctum)
     */
    public function destroy(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out']);
    }
}
