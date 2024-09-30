<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;

use Illuminate\Support\Facades\Route;

Route::get('/', [AuthController::class,'index']);
Route::get('/invoice', [AuthController::class,'invoice'])->middleware('auth');
Route::get('/reset', [AuthController::class,'reset']);


Route::post('/register-post', [AuthController::class,'store'])->middleware('auth');

Route::get('/pay', [AuthController::class,'pay']);
Route::get('/pin', [AuthController::class,'pin'])->middleware('auth');
Route::post('/validate-pin', [AuthController::class,'validatepin'])->middleware('auth');


Route::get('/products', [AuthController::class,'product'])->middleware('auth');

Route::post('/pay-post', [AuthController::class,'login']);


Route::get('/admin', [AdminController::class,'index'])->middleware('auth');

Route::get('/manage', [AdminController::class,'manage'])->middleware('auth');
Route::get('/delete/{id}', [AdminController::class,'delete'])->middleware('auth');

Route::get('/add-product', [AdminController::class,'addindex'])->middleware('auth');

Route::post('/add-product/post', [AdminController::class,'store'])->middleware('auth');


