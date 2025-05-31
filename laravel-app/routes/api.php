<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CommentController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


/*// Public routes
    // Get all the available profiles (or only id if parameter is given)
// Route::get('/profile/{id}', 'ApiController@getProfile');
Route::get('/profile/{id}', [ApiController::class, 'getProfile']);

// TODO add middleware auth for those routes
// Routes accessible by auth
    // Add comment to a profile => TODO check if better to have id in body or in path
// Route::post('/comment/{id}', 'ApiController@createComment');
Route::get('/comment/{id}', [ApiController::class, 'createComment']);
    // Update or delete a profile
// Route::put('/profile/{id}', 'ApiController@updateOrDeleteProfile');
// Route::delete('/profile/{id}', 'ApiController@updateOrDeleteProfile');
Route::put('/profile/{id}', [ApiController::class, 'updateOrDeleteProfile']);
Route::delete('/profile/{id}',[ApiController::class, 'updateOrDeleteProfile']);

// Routes accessible only to admin and through auth
    // Create a profile
// Route::post('/profile', 'ApiController@createProfile');
Route::post('/profile', [ApiController::class, 'createProfile']);*/

Route::apiResource('profiles', ProfileController::class);

Route::apiResource('profiles.comments', CommentController::class)->shallow();