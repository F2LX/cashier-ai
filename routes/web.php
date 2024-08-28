<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;

use Illuminate\Support\Facades\Route;

Route::get('/', [AuthController::class,'index']);
Route::post('/register-post', [AuthController::class,'store']);

Route::get('/pay', [AuthController::class,'pay']);
Route::get('/products', [AuthController::class,'product']);

Route::post('/pay-post', [AuthController::class,'login']);


Route::get('/admin', [AdminController::class,'index']);



