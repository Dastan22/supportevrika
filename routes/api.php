<?php


use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\IssueController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

//        PUBLIC ROUTES
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout']);
Route::post('register', [AuthController::class, 'register']);

// ISSUES
Route::apiResource('issues', IssueController::class);
// USERS
Route::apiResource('users', UserController::class)->middleware(['auth:sanctum' ,'admin']);


Route::group(['middleware' => 'auth:sanctum'], function() {
    // CATEGORIES
    Route::apiResource('categories', CategoryController::class);

    // USER's ISSUE MANIPULATIONS
    Route::get('users/{user}/issues', [IssueController::class, 'myIssues']);
    Route::put('users/{user}/issues/{issue}/takeJob', [IssueController::class, 'takeJob']);
    Route::put('users/{user}/issues/{issue}/return', [IssueController::class, 'return']);
    Route::put('users/{user}/issues/{issue}/complete', [IssueController::class, 'complete']);

});

















































