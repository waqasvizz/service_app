<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\BidController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\PostAssetsController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\AssignJobController;
use App\Http\Controllers\Api\ChatController;
use App\Http\Controllers\Api\FCM_TokenController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\PaymentController;

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


Route::post('register_user', [RegisterController::class, 'register']);
Route::post('login', [RegisterController::class, 'login']);
Route::post('forgot_password', [RegisterController::class, 'forgotPassword']);
Route::resource('payments', PaymentController::class);
     
Route::middleware('auth:api')->group( function () {
    
    Route::post('logout', [RegisterController::class, 'logoutProfile']);

    Route::get('get_profile', [RegisterController::class, 'getProfile']);
    Route::post('update_profile', [RegisterController::class, 'updateProfile']);
    Route::post('services/{id}', [ServiceController::class, 'update']);
    Route::post('bids/{id}', [BidController::class, 'update']);
    Route::post('posts/{id}', [PostController::class, 'update']);
    Route::post('reviews/{id}', [ReviewController::class, 'update']);
    Route::post('fcm_tokens/update', [FCM_TokenController::class, 'update']);
    Route::post('fcm_tokens/revoke', [FCM_TokenController::class, 'destroy']);

    //resouce routes
    Route::resource('assign_jobs', AssignJobController::class);
    Route::resource('services', ServiceController::class);
    Route::resource('bids', BidController::class);
    Route::resource('posts', PostController::class);
    Route::resource('reviews', ReviewController::class);
    Route::resource('post_assets', PostAssetsController::class);
    Route::resource('chats', ChatController::class);
    Route::resource('fcm_tokens', FCM_TokenController::class);
    Route::resource('notifications', NotificationController::class);
});

// to access the services without logged in to the system.
Route::get('services', [ServiceController::class, 'index']);