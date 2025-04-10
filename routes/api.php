<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
Route::middleware('auth:sanctum')->group(function () {
    // Get all users
    Route::get('/users', [UserController::class, 'index']);

    // Create user
    Route::post('/users', [UserController::class, 'store']);

    // Get specific user
    Route::get('/users/{id}', [UserController::class, 'show']);

    // Update user
    Route::put('/users/{id}', [UserController::class, 'update']);

    // Delete user
    Route::delete('/users/{id}', [UserController::class, 'destroy']);
});
Route::middleware('auth:sanctum')->get('/search', [SearchController::class, 'search']);
