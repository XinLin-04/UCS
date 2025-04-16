<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ComplaintController;

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

//Open the homepage
Route::get('home', [HomeController::class, 'index']);


// Test for Gate unitl here fuckdup
// Route::get('/posts/create', [CommentController::class, 'create']);
// Route::get('/posts/edit', [CommentController::class, 'edit']);
// Route::get('/posts/delete', [CommentController::class, 'delete']);