<?php



use Illuminate\Http\Request;
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\IssueController;






Route::group(['middleware' => 'auth:sanctum'], function() {
    Route::post('login', [AuthController::class, 'login']);
});

Route::group(['middleware' => 'auth:sanctum'], function() {
    Route::post("/users", [AuthController::class, 'logout']);
});



Route::group(['middleware' => ['auth:sanctum', 'admin']], function() {

    Route::post('logout', [AuthController::class,'logout']);

    Route::get('issues',[IssueController::class, 'index']);
    Route::get('issue/{id}',[IssueController::class, 'show']);
    Route::post('issue/add',[IssueController::class, 'store']);
    Route::post('issue/{id}/update', [IssueController::class, 'update']);
    Route::delete('issue/{id}/delete', [IssueController::class, 'destroy']);

    Route::get('categories', [CategoryController::class, 'index']);
    Route::get('category/{id}/show', [CategoryController::class, 'show']);
    Route::post('category/add', [CategoryController::class, 'store']);
    Route::post('category/{id}/update', [CategoryController::class, 'update']);
    Route::delete('category/{id}/delete', [CategoryController::class, 'destroy']);

    Route::get('statuses', [StatusController::class, 'index']);
    Route::post('status/add', [StatusController::class, 'store']);
    Route::get('status/{id}/show', [StatusController::class, 'show']);
    Route::post('status/{id}/update', [StatusController::class, 'update']);
    Route::delete('status/{id}/delete',[StatusController::class,'destroy']);

    Route::group(['middleware' => 'auth:sanctum'], function() {
        Route::post('register', [AuthController::class, 'register']);
    });



});





