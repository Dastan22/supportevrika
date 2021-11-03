<?php


use Illuminate\Http\Request;
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\IssueController;


//    Route::post('register', [AuthController::class, 'register']);
//    Route::post('/login', [AuthController::class, 'login'])->name('login');


    Route::group(['middleware' => ['auth:sanctum']], function() {
       Route::post('login', [AuthController::class, 'login']);
    });

    Route::group(['middleware' => 'auth:sanctum'], function() {
      Route::post("/users", [AuthController::class, 'logout']);
    });

      Route::post('issue/add',[IssueController::class, 'store']);




    Route::group(['middleware' => ['auth:sanctum', 'admin']], function() {

      Route::post('logout', [AuthController::class,'logout']);

      Route::get('issues',[IssueController::class, 'index']);
      Route::get('issue/{id}',[IssueController::class, 'show']);
      Route::post('issue/add',[IssueController::class, 'store']);
      Route::put('issue/{issue}', [IssueController::class, 'update']);
      Route::delete('issue/{issue}', [IssueController::class, 'destroy']);
      Route::post('issue/{issue}/take-job', [IssueController::class, 'beginWork']);






      Route::get('categories', [CategoryController::class, 'index']);
      Route::get('category/{id}/show', [CategoryController::class, 'show']);
      Route::post('category/add', [CategoryController::class, 'store']);
      Route::put('category/{category}', [CategoryController::class, 'update']);
      Route::delete('category/{id}', [CategoryController::class, 'destroy']);



//      Route::apiResource('/categories', CategoryController::class);
//    Route::group(['middleware' => 'auth:sanctum'], function() {
//    Route::post('register', [AuthController::class, 'register']);
//    });



     });





