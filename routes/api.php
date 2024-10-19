<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('users', [AuthController::class, 'index']);
Route::get('users/{id}', [AuthController::class, 'show']);

Route::middleware('auth:sanctum')->get('/profile', [AuthController::class, 'getProfile']);


Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::get('posts', [PostController::class, 'index']);
Route::post('posts', [PostController::class, 'store'])->middleware('auth:sanctum');
Route::get('posts/{post}', [PostController::class, 'show']);
Route::put('posts/{post}', [PostController::class, 'update'])->middleware('auth:sanctum');
Route::patch('posts/{post}', [PostController::class, 'update'])->middleware('auth:sanctum');

Route::delete('posts/{post}', [PostController::class, 'destroy'])->middleware('auth:sanctum');
