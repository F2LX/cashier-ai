<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AuthController::class,'index']);
Route::post('/login-post', [AuthController::class,'login']);

Route::get('/register', [AuthController::class,'register']);
Route::post('/register-post', [AuthController::class,'store']);

