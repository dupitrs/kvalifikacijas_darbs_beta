<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisteredUserController extends Controller
{
    public function store(\Illuminate\Http\Request $request)
    {
        $validated = $request->validate([
            'vards' => ['required', 'string', 'max:100'],
            'uzvards' => ['required', 'string', 'max:100'],
            'epasts' => ['required', 'string', 'email', 'max:150', 'unique:lietotaji,epasts'],
            'parole' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = \App\Models\User::create([
            'vards' => $validated['vards'],
            'uzvards' => $validated['uzvards'],
            'epasts' => $validated['epasts'],
            'parole' => \Illuminate\Support\Facades\Hash::make($validated['parole']),
            'loma' => 'lietotajs',
            'punkti' => 0,
            'blokets' => 0,
            // limenis_id atstāj null, ja nav obligāts
        ]);

        // ja gribi uzreiz ielogot pēc reģistrācijas:
        \Illuminate\Support\Facades\Auth::login($user);

        // ja izmanto Sanctum tokenus:
        $token = $user->createToken('api')->plainTextToken;

        return response()->json([
            'message' => 'Registered',
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'vards' => $user->vards,
                'uzvards' => $user->uzvards,
                'epasts' => $user->epasts,
                'loma' => $user->loma,
            ],
        ], 201);
    }

}
