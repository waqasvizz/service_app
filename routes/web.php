<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MosqueController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PostAssetsController;
use App\Http\Controllers\PostController;
use RahulHaque\Filepond\Http\Controllers\FilepondController;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/clear-cache', function() {
    Artisan::call('cache:clear');
    Artisan::call('optimize');
    Artisan::call('route:cache');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    Artisan::call('config:cache');
    return '<h1>Cache facade value cleared</h1>';
});

Route::get('/schedule-run', function() {
    Artisan::call("schedule:run");
    return '<h1>schedule run activated</h1>';
});

Route::get('/site-down', function() {
    Artisan::call('down --secret="harrypotter"');
    return '<h1>Application is now in maintenance mode.</h1>';
});

Route::get('/site-up', function() {
    Artisan::call('up');
    return '<h1>Application is now live..</h1>';
});

Route::get('/run-seeder', function() {
    Artisan::call("db:seed");
    return '<h1>Dummy data added successfully</h1>';
});

Route::get('/storage-link', function() {
    Artisan::call("storage:link");
    return '<h1>storage link activated</h1>';
});
    
Route::get('/queue-work', function() {
    Artisan::call("queue:work");
    return '<h1>queue work activated</h1>';
});
    
Route::get('/migration-refresh', function() {
    // Artisan::call("migrate:fresh");
    Artisan::call('migrate:refresh');    
    // Artisan::call('passport:install --force');    
    Artisan::call('passport:install');

    return '<h1>Migration refresh successfully</h1>';
});

// Route::get('/', function () {
//     return view('welcome');
// });

// Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();
Route::get('/test-now', [UserController::class, 'testing']);
Route::get('/', [UserController::class, 'welcome']);
Route::get('/login', [UserController::class, 'login'])->name('login');
Route::get('/logout', [UserController::class, 'logout']);
// ->name('logout');

Route::get('/register', [UserController::class, 'register'])->name('register');
Route::get('/forgot-password', [UserController::class, 'forgotPassword'])->name('forgotPassword');
Route::get('/reset-password', [UserController::class, 'resetPassword'])->name('resetPassword');

Route::post('/accountRegister', [UserController::class, 'accountRegister'])->name('accountRegister');
Route::post('/accountLogin', [UserController::class, 'accountLogin'])->name('accountLogin');
Route::post('/resetPassword', [UserController::class, 'accountResetPassword'])->name('accountResetPassword');

Route::post('/save_payment_response', [PaymentController::class, 'savePaymentResponse'])->name('save_payment');
Route::get('/items', [PaymentController::class, 'cart']);
Route::get('/items-stripe', [PaymentController::class, 'cartStripe'])->name('stripe_payment');
Route::post('/payment', [PaymentController::class, 'payment'])->name('payment');
Route::get('/payment-success', [PaymentController::class, 'paymentSuccess'])->name('success.pay');

Route::middleware(['auth'])->group(function () {

    Route::get('/filepond_record_get', [FilepondController::class, 'get_records']);
    Route::get('/filepond_record_destroy', [FilepondController::class, 'destroy_records']);
    
    Route::get('/admin', [UserController::class, 'dashboard']);
    Route::resource('service', ServiceController::class);
    Route::resource('category', CategoryController::class);
    Route::resource('role', RoleController::class);
    Route::resource('user', UserController::class);
    Route::resource('post', PostController::class);
    Route::resource('post_asset', PostAssetsController::class);
});