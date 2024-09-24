<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;

use Illuminate\Support\Facades\Route;

Route::get('/', [AuthController::class,'index']);
Route::get('/invoice', [AuthController::class,'invoice']);
Route::get('/reset', [AuthController::class,'reset']);


Route::post('/register-post', [AuthController::class,'store']);

Route::get('/pay', [AuthController::class,'pay']);
Route::get('/pin', [AuthController::class,'pin']);
Route::post('/validate-pin', [AuthController::class,'validatepin']);


Route::get('/products', [AuthController::class,'product']);

Route::post('/pay-post', [AuthController::class,'login']);


Route::get('/admin', [AdminController::class,'index']);

Route::get('/manage', [AdminController::class,'manage']);

Route::get('/add-product', [AdminController::class,'addindex']);

Route::post('/add-product/post', [AdminController::class,'store']);

