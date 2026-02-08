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
| Visi endpointi atgriež JSON.
| Azure bug: controller route mapping dažiem POST dod nginx 404.
| Tāpēc login/logout taisām kā closure, kas tieši izsauc kontrolieri.
*/

// ----------------------------
// Health / info
// ----------------------------
Route::get('/ping', fn () => response()->json(['ok' => true]));

Route::get('/rf', function () {
    return response()->json([
        'rf'   => 'RF-2026-02-08-01',
        'time' => now()->toDateTimeString(),
    ]);
});

Route::get('/version', function () {
    return response()->json([
        'version' => '2026-02-08-POSTFIX1',
        'time'    => now()->toDateTimeString(),
        'env'     => app()->environment(),
    ]);
});

Route::get('/release', function () {
    return response()->json([
        'time'    => now()->toDateTimeString(),
        'release' => env('APP_RELEASE', 'no_release_set'),
    ]);
});

// ----------------------------
// DB test
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
// Env / paths
// ----------------------------
Route::get('/whoami', function () {
    return response()->json([
        'base_path'               => base_path(),
        'app_path'                => app_path(),
        'storage_path'            => storage_path(),
        'app_env'                 => app()->environment(),
        'routes_file_exists_root' => file_exists(base_path('routes/api.php')),
        'routes_file_exists_api'  => file_exists(base_path('api/routes/api.php')),
    ]);
});

// ----------------------------
// Echo (debug)
/// ----------------------------
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

// ----------------------------
// Diag: controller existence
// ----------------------------
Route::get('/diag/auth-controller', function () {
    $class = AuthenticatedSessionController::class;

    return response()->json([
        'class'       => $class,
        'class_exists'=> class_exists($class),
        'file_exists' => file_exists(app_path('Http/Controllers/Auth/AuthenticatedSessionController.php')),
    ]);
});

// Tiešs kontroliera store() izsaukums (debug)
Route::post('/diag/auth-controller-call', function (Request $r) {
    try {
        return app(AuthenticatedSessionController::class)->store($r);
    } catch (\Throwable $e) {
        Log::error('diag/auth-controller-call failed', [
            'exception' => get_class($e),
            'message'   => $e->getMessage(),
        ]);

        return response()->json([
            'ok'        => false,
            'exception' => get_class($e),
            'message'   => $e->getMessage(),
        ], 500);
    }
});

// ----------------------------
// Cache clear (ar secret)
// ----------------------------
// Azure App Settings uzliec: CLEAR_SECRET=xxx
// Saukšana: GET /api/clear?secret=xxx
Route::get('/clear', function (Request $r) {
    $secret = env('CLEAR_SECRET');

    if (!$secret || $r->query('secret') !== $secret) {
        return response()->json(['ok' => false, 'message' => 'forbidden'], 403);
    }

    try {
        Artisan::call('optimize:clear');

        return response()->json([
            'ok'     => true,
            'time'   => now()->toDateTimeString(),
            'output' => Artisan::output(),
        ]);
    } catch (\Throwable $e) {
        Log::error('clear failed', [
            'exception' => get_class($e),
            'message'   => $e->getMessage(),
        ]);

        return response()->json(['ok' => false, 'message' => $e->getMessage()], 500);
    }
});

// ----------------------------
// AUTH
// ----------------------------

// Register (tev jau strādā)
Route::post('/auth/register', [RegisteredUserController::class, 'store']);

// LOGIN: closure -> tiešs kontroliera izsaukums (APIEJ nginx 404 gļuku)
Route::post('/auth/login', function (Request $r) {
    return app(AuthenticatedSessionController::class)->store($r);
});

// LOGOUT: closure -> tiešs kontroliera izsaukums (drošībai arī apiet mapping)
Route::post('/auth/logout', function (Request $r) {
    return app(AuthenticatedSessionController::class)->destroy($r);
})->middleware('auth:sanctum');

// User no tokena
Route::get('/user', fn (Request $r) => $r->user())->middleware('auth:sanctum');

// ----------------------------
// Routes list (debug)
/// ----------------------------
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
        'api'   => $routes->filter(fn ($x) => str_starts_with($x['uri'], 'api/'))->take(150)->values(),
    ]);
});
