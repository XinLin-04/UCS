<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\SearchController;
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

// Resend Email Verification (for unverified users)
Route::post('/email/resend-unverified', [VerificationController::class, 'resendForUnverifiedUser'])
    ->name('verification.resend.unverified');

// Authentication routes (with email verification)
Auth::routes(['verify' => true]);

// Home/Main page (open to all)
Route::get('/', [ComplaintController::class, 'index'])->name('mainPage');

// Complaint Routes
Route::get('/complaints', [ComplaintController::class, 'index'])->name('complaints.index'); // All can view
Route::get('/complaints/{complaint}', [ComplaintController::class, 'show'])->name('complaints.show'); // All can view

// Only authenticated users can create/update/delete
Route::middleware('auth')->group(function () {
    Route::post('/complaints', [ComplaintController::class, 'store'])->name('complaints.store');
    Route::put('/complaints/{complaint}', [ComplaintController::class, 'update'])->name('complaints.update');
    Route::delete('/complaints/{complaint}', [ComplaintController::class, 'destroy'])->name('complaints.destroy');

    // Comment Routes
    Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::put('/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

    // Get current user's posts
    Route::get('/user/posts', [ComplaintController::class, 'userComplaints'])->name('user.posts');
});
