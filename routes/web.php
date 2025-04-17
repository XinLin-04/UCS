<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
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

// Define the search route
Route::get('/search', [SearchController::class, 'index'])->name('search');

Auth::routes(['verify' => true]);

Route::middleware(['auth', 'verified'])->group(function () {
    // Verified-only routes here
});

Route::get('/homeTest', function () {
    return view('homeTest');  // This will load the homeTest.blade.php view
});

// Define the search route
Route::get('/search', [SearchController::class, 'index'])->name('search');

Auth::routes(['verify' => true]);

Route::middleware(['auth', 'verified'])->group(function () {
    // Verified-only routes here
});

Route::get('/homeTest', function () {
    return view('homeTest');  // This will load the homeTest.blade.php view
});

Route::get('/', [ComplaintController::class, 'index']);

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Complaint routes with appropriate middleware
Route::post('/complaints', [ComplaintController::class, 'store'])->middleware('auth')->name('complaints.store');
Route::get('/complaints', [ComplaintController::class, 'index'])->name('complaints.index');
Route::put('/complaints/{complaint}', [ComplaintController::class, 'update'])->middleware('auth')->name('complaints.update');
Route::delete('/complaints/{complaint}', [ComplaintController::class, 'destroy'])->middleware('auth')->name('complaints.destroy');