<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\RedirectController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::resource('redirects', RedirectController::class);

Route::get('redirects/{redirect}/logs', [RedirectController::class, 'logs']);
Route::get('redirects/{redirect}/stats', [RedirectController::class, 'stats']);
