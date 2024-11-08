<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/register', [AuthController::class, 'register']);

// ->middleware('auth:sanctum');
