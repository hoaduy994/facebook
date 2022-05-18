<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::group(['prefix' => 'auth'], function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
   
}); 

Route::group([
    // 'middleware' => 'api'
    'middleware' => ['auth:api', 'api'],
   
    // 'middleware' => ['api', 'auth:api'],
], function () {
    
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::post('/posts', [HomeController::class, 'createPost']);
    Route::post('/uposts', [HomeController::class, 'updatePost']);
    Route::get('/posts', [HomeController::class, 'index']);
    Route::post('/posts/{id}', [HomeController::class, 'updatePost']);
    Route::delete('/posts/{id}', [HomeController::class, 'deletePost']);
    // Route::post('/posts', [AuthController::class, 'createPost']);

    Route::get('/user-profile', [AuthController::class, 'userProfile']);    

});
