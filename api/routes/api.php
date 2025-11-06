<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

Route::post('/auth/token-login', function (Request $request) {
    $request->validate(['email'=>'required|email','password'=>'required']);
    $user = User::where('email',$request->email)->first();
    if (!$user || !Hash::check($request->password, $user->password)) {
        return response()->json(['message'=>'Invalid credentials'], 422);
    }
    $token = $user->createToken('spa')->plainTextToken;
    return response()->json(['token'=>$token, 'user'=>$user]);
});

Route::middleware('auth:sanctum')->post('/auth/token-logout', function (Request $request) {
    $request->user()->currentAccessToken()->delete();
    return response()->json(['ok'=>true]);
});


Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});
