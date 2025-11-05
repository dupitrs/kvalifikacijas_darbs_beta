<?php
use Illuminate\Support\Facades\Route;
Route::get('/', fn()=> 'BDUS API darbojas');
Route::get('/health', fn()=> response()->json(['ok'=>true]));
