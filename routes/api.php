<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\CommentController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// User's complaints
Route::middleware('auth:sanctum')->get('/user/complaints', [ComplaintController::class, 'userComplaints']);

// Filtered complaints
Route::get('/complaints', [ComplaintController::class, 'getFiltered']);

// Single complaint details (for AJAX)
Route::get('/complaints/{complaint}/details', [ComplaintController::class, 'getComplaint']);

// Comments for a complaint
Route::get('/complaints/{complaint}/comments', [CommentController::class, 'index']);

// Add comment to a complaint
Route::middleware('auth:sanctum')->post('/complaints/{complaint}/comments', [CommentController::class, 'store']);

Route::middleware('auth:sanctum')->get('/user/complaints', [ComplaintController::class, 'userComplaints']);
