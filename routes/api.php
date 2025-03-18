<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

//Route::get('/user', function (Request $request) {
//    return $request->user();
//})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->apiResource('tasks', TaskController::class);
Route::middleware('throttle:5,1')->post('login', [AuthController::class,'login'])->name('api.login');
Route::post('register', [AuthController::class,'register'])->name('api.register');
Route::middleware('auth:sanctum')->get('/logout', [AuthController::class,'logout'])->name('');