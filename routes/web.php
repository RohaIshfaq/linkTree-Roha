<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

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

Route::get('/', function () {
    return view('welcome');
});

//auth route for both 
Route::group(['middleware' => ['auth']], function() { 
    Route::get('/dashboard',
     [DashboardController::class, 'index'])->name('dashboard')->middleware('verified');
});


// for users
Route::group(['middleware' => ['auth', 'role:user']], function() { 
    Route::get('/dashboard/myprofile', 
    [DashboardController::class, 'myprofile'])
    ->name('dashboard.myprofile');
});


Route::get('/verify-email', [EmailVerificationPromptController::class, '__invoke'])
  ->middleware('auth')
  ->name('verification.notice');

  Route::get('/verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
  ->middleware(['auth', 'signed', 'throttle:6,1'])
  ->name('verification.verify');

  
Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
->middleware(['auth', 'throttle:6,1'])
->name('verification.send');






require __DIR__.'/auth.php';
