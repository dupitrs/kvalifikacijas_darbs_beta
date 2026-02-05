<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'service' => 'BDUS API',
        'status' => 'ok',
        'version' => app()->version(),
    ]);
});
 