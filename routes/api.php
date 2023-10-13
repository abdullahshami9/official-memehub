<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\LoginController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('user/data', [LoginController::class, 'userdata']);
Route::post('user/login', [AuthenticatedSessionController::class, 'store']);
Route::post('user/register', [RegisteredUserController::class, 'store']);
