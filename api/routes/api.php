<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

/*
|--------------------------------------------------------------------------
| API Routes (JSON only)
|--------------------------------------------------------------------------
| Šeit ir tikai API maršruti.
| Piezīme: Azure vidē POST uz /api/auth/login dod nginx 404,
| tāpēc login izmantojam /api/login.
*/

// ----------------------------------------------------
// Identitāte / veselības pārbaudes
// ----------------------------------------------------

Route::get('/rf', function () {
    return response()->json([
        'rf' => 'RF-2026-02-08-01',
        'time' => now()->toDateTimeString(),
    ]);
});

Route::get('/version', function () {
    return response()->json([
        'version' => '2026-02-08-01',
        'time' => now()->toDateTimeString(),
        'env' => app()->environment(),
    ]);
});

Route::get('/release', function () {
    return response()->json([
        'time' => now()->toDateTimeString(),
        'release' => env('APP_RELEASE', 'no_release_set'),
    ]);
});

Route::get('/ping', function () {
    return response()->json(['ok' => true]);
});

// ----------------------------------------------------
// DB tests
// ----------------------------------------------------

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

// ----------------------------------------------------
// Environment / paths
// ----------------------------------------------------

Route::get('/whoami', function () {
    return response()->json([
        'base_path' => base_path(),
        'app_path' => app_path(),
        'storage_path' => storage_path(),
        'app_env' => app()->environment(),
        'routes_file_exists_root' => file_exists(base_path('routes/api.php')),
        'routes_file_exists_api' => file_exists(base_path('api/routes/api.php')),
    ]);
});

// ----------------------------------------------------
// Echo (debug: body/headers)
// ----------------------------------------------------

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

// ----------------------------------------------------
// Diag: Auth controller esamība + tiešs izsaukums
// ----------------------------------------------------

Route::get('/diag/auth-controller', function () {
    $class = AuthenticatedSessionController::class;

    return response()->json([
        'class' => $class,
        'class_exists' => class_exists($class),
        'file_exists' => file_exists(app_path('Http/Controllers/Auth/AuthenticatedSessionController.php')),
    ]);
});

Route::post('/diag/auth-controller-call', function (Request $r) {
    $class = AuthenticatedSessionController::class;

    try {
        if (!class_exists($class)) {
            return response()->json([
                'ok' => false,
                'error' => 'class_not_found',
                'class' => $class,
            ], 500);
        }

        $controller = app($class);
        return $controller->store($r);
    } catch (\Throwable $e) {
        Log::error('diag/auth-controller-call failed', [
            'exception' => get_class($e),
            'message' => $e->getMessage(),
        ]);

        return response()->json([
            'ok' => false,
            'exception' => get_class($e),
            'message' => $e->getMessage(),
        ], 500);
    }
});

// ----------------------------------------------------
// Cache clear (ar secret)
// ----------------------------------------------------
// Azure App Settings pievieno: CLEAR_SECRET=kkkkkkkk (jebkas)
// Tad sauc: GET /api/clear?secret=kkkkkkkk
Route::get('/clear', function (Request $r) {
    $secret = env('CLEAR_SECRET');

    if ($secret) {
        if ($r->query('secret') !== $secret) {
            return response()->json(['ok' => false, 'message' => 'forbidden'], 403);
        }
    } else {
        // Ja secret nav uzlikts, tad drošības pēc aizliedzam
        return response()->json([
            'ok' => false,
            'message' => 'CLEAR_SECRET not set in App Settings',
        ], 403);
    }

    try {
        Artisan::call('optimize:clear');
        return response()->json([
            'ok' => true,
            'time' => now()->toDateTimeString(),
            'output' => Artisan::output(),
        ]);
    } catch (\Throwable $e) {
        Log::error('clear failed', [
            'exception' => get_class($e),
            'message' => $e->getMessage(),
        ]);

        return response()->json([
            'ok' => false,
            'message' => $e->getMessage(),
        ], 500);
    }
});

// ----------------------------------------------------
// AUTH
// ----------------------------------------------------

// ✅ GALVENAIS LOGIN ceļš (Azure dēļ)
Route::post('/login', [AuthenticatedSessionController::class, 'store']);

// Reģistrācija var palikt zem /auth/register
Route::prefix('auth')->group(function () {
    Route::post('/register', [RegisteredUserController::class, 'store']);

    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
        ->middleware('auth:sanctum');
});

// ----------------------------------------------------
// User no tokena
// ----------------------------------------------------

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// ----------------------------------------------------
// Routes list (debug)
// ----------------------------------------------------

Route::get('/_routes', function () {
    $routes = collect(Route::getRoutes())->map(function ($r) {
        return [
            'methods' => $r->methods(),
            'uri'     => $r->uri(),
            'action'  => $r->getActionName(),
        ];
    });

    return response()->json([
        'count' => $routes->count(),
        'auth'  => $routes->filter(fn ($x) => str_contains($x['uri'], 'api/auth'))->values(),
        'api'   => $routes->filter(fn ($x) => str_starts_with($x['uri'], 'api/'))->take(120)->values(),
    ]);
});
