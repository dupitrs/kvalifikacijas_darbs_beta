<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
// use Illuminate\Support\Facades\Auth; // ja gribi paturēt Auth::login

class RegisteredUserController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'vards' => ['required', 'string', 'max:100'],
            'uzvards' => ['required', 'string', 'max:100'],
            'epasts' => ['required', 'string', 'email', 'max:150', 'unique:lietotaji,epasts'],

            // ✅ jaunie obligātie lauki
            'personas_kods' => ['required', 'string', 'max:50'],
            'adrese' => ['required', 'string', 'max:255'],

            // ✅ parole + parole_confirmation no fronta
            'parole' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'vards' => $validated['vards'],
            'uzvards' => $validated['uzvards'],
            'epasts' => $validated['epasts'],

            // ✅ saglabā DB
            'personas_kods' => $validated['personas_kods'],
            'adrese' => $validated['adrese'],

            'parole' => Hash::make($validated['parole']),

            // default loģika
            'loma' => 'lietotajs',
            'punkti' => 0,
            'blokets' => 0,
            // limenis_id paliek null
        ]);

        // ⚠️ API režīmā šo īstenībā nevajag (sesijas nelieto)
        // Auth::login($user);

        // Sanctum token
        $token = $user->createToken('api')->plainTextToken;

        return response()->json([
            'message' => 'Registered',
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'vards' => $user->vards,
                'uzvards' => $user->uzvards,
                'epasts' => $user->epasts,
                'personas_kods' => $user->personas_kods,
                'adrese' => $user->adrese,
                'loma' => $user->loma,
                'blokets' => $user->blokets,
                'punkti' => $user->punkti,
                'limenis_id' => $user->limenis_id,
            ],
        ], 201);
    }
}
