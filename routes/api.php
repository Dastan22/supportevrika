<?php


use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\IssueController;


Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);



Route::middleware('auth:sanctum')->group(function (){

    Route::post('logout', [AuthController::class,'logout']);

    Route::get('issues',[IssueController::class, 'index']);
    Route::get('issue/{id}/show',[IssueController::class, 'show']);
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





});





//Route::apiResources([
//    'desks' => DeskController::class,
//
//]);

