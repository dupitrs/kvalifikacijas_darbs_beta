<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

Route::get('/version', function () {
    return response()->json([
        'version' => '2026-02-06-01',
        'time' => now()->toDateTimeString(),
    ]);
});


Route::get('/_clear', function () {
    Artisan::call('optimize:clear');
    return response()->json([
        'ok' => true,
        'output' => Artisan::output(),
    ]);
});

Route::get('/dbtest', function () {
    try {
        DB::connection()->getPdo();
        return response()->json(['db' => 'ok']);
    } catch (\Throwable $e) {
        return response()->json([
            'db' => 'fail',
            'error' => $e->getMessage(),
        ], 500);
    }
});


Route::get('/whoami', function () {
    return response()->json([
        'base_path' => base_path(),
        'app_env' => app()->environment(),
        'routes_file_exists_root' => file_exists(base_path('routes/api.php')),
        'routes_file_exists_api' => file_exists(base_path('api/routes/api.php')),
    ]);
});

Route::get('/ping', function () {
    return response()->json(['ok' => true]);
});

Route::prefix('auth')->group(function () {
    Route::post('/register', [RegisteredUserController::class, 'store']);
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
        ->middleware('auth:sanctum');
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::any('/echo', function (Request $r) {
    return response()->json([
        'method' => $r->method(),
        'content_type' => $r->header('content-type'),
        'accept' => $r->header('accept'),
        'raw' => $r->getContent(),
        'all' => $r->all(),
        'json_all' => $r->json()->all(),
        'has_json' => $r->isJson(),
    ]);
});

Route::post('/login-test', function () {
    return response()->json(['ok' => true]);
});


Route::get('/_clear', function () {
    Artisan::call('optimize:clear');
    return response()->json(['ok' => true, 'output' => Artisan::output()]);
});

