<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\Auth\RegisterController;


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
Route::post('/email/resend-unverified', [App\Http\Controllers\Auth\VerificationController::class, 'resendForUnverifiedUser'])
    ->name('verification.resend.unverified');

// Define the search route
Route::get('/search', [SearchController::class, 'index'])->name('search');

Auth::routes(['verify' => true]);

Route::get('/', [App\Http\Controllers\ComplaintController::class, 'index'])
    ->name('mainPage');

// Route::middleware(['auth', 'verified'])->group(function () {
//     // Verified-only routes here
// });

// Complaint routes with appropriate middleware
// Route::post('/complaints', [ComplaintController::class, 'store'])->middleware('auth')->name('complaints.store');
// Route::get('/complaints', [ComplaintController::class, 'index'])->name('complaints.index');
// Route::put('/complaints/{complaint}', [ComplaintController::class, 'update'])->middleware('auth')->name('complaints.update');
// Route::delete('/complaints/{complaint}', [ComplaintController::class, 'destroy'])->middleware('auth')->name('complaints.destroy');

Route::post('/complaints', [ComplaintController::class, 'store'])->name('complaints.store');
Route::get('/complaints', [ComplaintController::class, 'index'])->name('complaints.index');
Route::put('/complaints/{complaint}', [ComplaintController::class, 'update'])->name('complaints.update');
Route::delete('/complaints/{complaint}', [ComplaintController::class, 'destroy'])->name('complaints.destroy');