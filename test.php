<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\UserController;



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
Route::post('/profile/update', [UserController::class, 'updateProfilePicture'])->name('profile.update');

Route::post('/search', [SearchController::class, 'search'])->name('search');

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
Route::get('/complaints/{complaint}', [ComplaintController::class, 'show'])->name('complaints.show');
Route::put('/complaints/{complaint}', [ComplaintController::class, 'update'])->middleware('auth')->name('complaints.update');
Route::delete('/complaints/{complaint}', [ComplaintController::class, 'destroy'])->middleware('auth')->name('complaints.destroy');

// Comment routes with middleware
Route::post('/comments', [CommentController::class, 'store'])->middleware('auth')->name('comments.store');
Route::put('/comments/{comment}', [CommentController::class, 'update'])->middleware('auth')->name('comments.update');
Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->middleware('auth')->name('comments.destroy');
Route::middleware('auth')->get('/user/posts', [ComplaintController::class, 'userComplaints'])->name('user.posts');