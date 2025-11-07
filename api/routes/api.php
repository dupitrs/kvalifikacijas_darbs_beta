<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/health', function () {
    return response()->json([
        'ok' => true,
        'ts' => now()->toIso8601String(),
    ]);
});

// api/routes/api.php (pievieno faila beigās)

Route::get('/public/summary', function () {
    return response()->json([
        'users'         => (int) DB::table('users')->count(),
        'sludinajumi'   => (int) DB::table('sludinajums')->count(),
        'pieteikumi'    => (int) DB::table('pieteikums')->count(),
        'parskati'      => (int) DB::table('parskats')->count(),
        'apliecinajumi' => (int) DB::table('apliecinajums')->count(),
    ]);
});

