<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\StoryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ReactionController;
use App\Http\Controllers\PostDetailController;
use app\Models\User;
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
Route::post('/story', [StoryController::class, 'createStory']);
Route::group([
    'middleware' => 'auth:api',
    'prefix' => 'user'
], function () {
    Route::get('me', [ProfileController::class, 'me']);
    // Route::post('status/new', 'StatusUpdatesController@store');
    // Route::post('image-upload', 'UserImageController@store');
    // Route::get('addFriend/{id}', 'UserController@toggleFriend');
    // Route::get('getFriends', 'UserController@getFriends');
});
Route::post('/reset-password', [ResetPasswordController::class, 'sendMail']);
Route::put('/reset-password/{token}', [ResetPasswordController::class,'reset']);

Route::post('/comment', [CommentController::class, 'createComment']);
Route::post('/update_comment/{id}', [CommentController::class, 'updateComment']);
Route::delete('/delete_comment/{id}', [CommentController::class, 'deleteComment']);


//reaction_post
Route::post('/like/{id}', [ReactionController::class, 'reaction_like']); 
Route::post('/love/{id}', [ReactionController::class, 'reaction_love']); 
Route::post('/wow/{id}', [ReactionController::class, 'reaction_wow']); 
Route::post('/haha/{id}', [ReactionController::class, 'reaction_haha']); 
Route::post('/angry/{id}', [ReactionController::class, 'reaction_angry']); 
Route::post('/sad/{id}', [ReactionController::class, 'reaction_sad']); 
Route::post('/post_detail/{id}', [PostDetailController::class, 'post_detail']); 
//
