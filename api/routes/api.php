<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

Route::get('/ping', fn () => response()->json(['ok' => true]));

Route::get('/dbtest', function () {
    try {
        DB::connection()->getPdo();
        return response()->json(['db' => 'ok']);
    } catch (\Throwable $e) {
        return response()->json(['db' => 'fail', 'error' => $e->getMessage()], 500);
    }
});

// Debug (var atstāt uz laiku)
Route::any('/echo', function (Request $r) {
    return response()->json([
        'method'   => $r->method(),
        'raw'      => $r->getContent(),
        'all'      => $r->all(),
        'json_all' => $r->json()->all(),
        'has_json' => $r->isJson(),
    ]);
});

// Register
Route::post('/register', [RegisteredUserController::class, 'store']);

// Login (NE auth/login)
Route::post('/session', function (Request $r) {
    return app(AuthenticatedSessionController::class)->store($r);
});

// Logout
Route::post('/session/logout', function (Request $r) {
    return app(AuthenticatedSessionController::class)->destroy($r);
})->middleware('auth:sanctum');

// Current user
Route::get('/me', fn (Request $r) => $r->user())->middleware('auth:sanctum');
