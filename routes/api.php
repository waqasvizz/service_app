<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\BidController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\PostAssetsController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::post('register', [RegisterController::class, 'register']);
Route::post('login', [RegisterController::class, 'login']);
Route::post('forgot_password', [RegisterController::class, 'forgotPassword']);
     
Route::middleware('auth:api')->group( function () {

    Route::post('services/{id}', [ServiceController::class, 'update']);
    Route::post('bids/{id}', [BidController::class, 'update']);
    Route::post('posts/{id}', [PostController::class, 'update']);

    //resouce routes
    Route::resource('services', ServiceController::class);
    Route::resource('bids', BidController::class);
    Route::resource('posts', PostController::class);
    Route::resource('post_assets', PostAssetsController::class);
});
// Route::resource('services', ServiceController::class);