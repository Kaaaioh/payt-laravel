<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\RedirectController;


Route::get('/r/{code}', [RedirectController::class, 'redirect']);
