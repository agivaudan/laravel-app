<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\AuthController;

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

// PUBLIC ROUTES
Route::get('/profiles', [ProfileController::class, 'index'])->name('profiles.index');

// Athentication
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::get('/login', [AuthController::class, 'login_redirect'])->name('login.redirect');

// PRIVATE ROUTES
Route::middleware('auth:sanctum')->group( function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    Route::apiResource('profiles', ProfileController::class)->except(['index']);
    Route::apiResource('profiles.comments', CommentController::class)->shallow();
});