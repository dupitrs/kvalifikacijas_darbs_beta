<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

/*
|--------------------------------------------------------------------------
| API Routes (JSON only)
|--------------------------------------------------------------------------
| Azure Linux + PHP dažreiz dod nginx 404 konkrētiem POST ceļiem (piem. /auth/login).
| Tāpēc izmantojam closure un login ceļu pārsaucam uz /auth/signin.
*/

// ----------------------------
// Health / info
// ----------------------------
Route::get('/ping', fn () => response()->json(['ok' => true]));

Route::get('/version', fn () => response()->json([
    'version' => '2026-02-08-FIX1',
    'time'    => now()->toDateTimeString(),
    'env'     => app()->environment(),
]));

Route::get('/release', fn () => response()->json([
    'time'    => now()->toDateTimeString(),
    'release' => env('APP_RELEASE', 'no_release_set'),
]));

// ----------------------------
// DB test (drošs, neko nemaina)
// ----------------------------
Route::get('/dbtest', function () {
    try {
        DB::connection()->getPdo();
        return response()->json(['db' => 'ok']);
    } catch (\Throwable $e) {
        return response()->json([
            'db'    => 'fail',
            'error' => $e->getMessage(),
        ], 500);
    }
});

// ----------------------------
// AUTH
// ----------------------------

// Register (strādā ar tavu BDUS lietotaji shēmu)
Route::post('/auth/register', [RegisteredUserController::class, 'store']);

/**
 * SIGNIN (NEVIS /auth/login)
 * Azure dažreiz “nogriež” tieši /auth/login ar nginx 404.
 * Tāpēc lieto šo ceļu frontendā.
 */
Route::post('/auth/signin', function (Request $r) {
    return app(AuthenticatedSessionController::class)->store($r);
});

// Logout (token-based)
Route::post('/auth/logout', function (Request $r) {
    return app(AuthenticatedSessionController::class)->destroy($r);
})->middleware('auth:sanctum');

// User no tokena
Route::get('/user', fn (Request $r) => $r->user())
    ->middleware('auth:sanctum');

// ----------------------------
// Debug (atstāj uz laiku; vēlāk var dzēst)
// ----------------------------

// Echo – palīdz redzēt, vai JSON body atnāk pareizi
Route::any('/echo', function (Request $r) {
    return response()->json([
        'method'       => $r->method(),
        'content_type' => $r->header('content-type'),
        'accept'       => $r->header('accept'),
        'raw'          => $r->getContent(),
        'all'          => $r->all(),
        'json_all'     => $r->json()->all(),
        'has_json'     => $r->isJson(),
    ]);
});

// Routes list – īslaicīgi diagnostikai
Route::get('/_routes', function () {
    $routes = collect(Route::getRoutes())->map(function ($rt) {
        return [
            'methods' => $rt->methods(),
            'uri'     => $rt->uri(),
            'action'  => $rt->getActionName(),
        ];
    });

    return response()->json([
        'count' => $routes->count(),
        'api'   => $routes->filter(fn ($x) => str_starts_with($x['uri'], 'api/'))
            ->take(150)
            ->values(),
    ]);
});

// Cache clear (tikai ar secret; noder, ja Azure cache iesprūst)
Route::get('/clear', function (Request $r) {
    $secret = env('CLEAR_SECRET');

    if (!$secret || $r->query('secret') !== $secret) {
        return response()->json(['ok' => false, 'message' => 'forbidden'], 403);
    }

    Artisan::call('optimize:clear');

    return response()->json([
        'ok'     => true,
        'time'   => now()->toDateTimeString(),
        'output' => Artisan::output(),
    ]);
});
