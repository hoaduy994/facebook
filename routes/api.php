<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FriendController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\GroupControllers;
use App\Http\Controllers\ResetPasswordController;
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
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/send_token', [ResetPasswordController::class,'sendMail'])->name('send_token');
    Route::post('/reset_password',[ResetPasswordController::class,'reset'])->name('reset_password');
    Route::post('/change_password',[AuthController::class,'changePassword'])->name('change_password');

    Route::get('/user-profile/{id}', [AuthController::class, 'userProfile'])->name('profile'); 
    Route::post('/user/{id}', [AuthController::class,'editProfile'])->name('editProfile');
}); 

Route::group([
    // 'middleware' => 'api'
    'middleware' => ['auth:api', 'api'],
   
    // 'middleware' => ['api', 'auth:api'],
], function () {
    
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    //post
    Route::post('/posts', [HomeController::class, 'createPost'])->name('createPost');
    Route::post('/uposts', [HomeController::class, 'updatePost']);
    Route::get('/posts', [HomeController::class, 'index'])->name('index');
    Route::post('/posts/{id}', [HomeController::class, 'updatePost'])->name('updatePost');
    Route::delete('/posts/{id}', [HomeController::class, 'deletePost'])->name('deletePost');
    // Route::post('/posts', [AuthController::class, 'createPost']);
    Route::post('/stories_text', [HomeController::class, 'createStories'])->name('createStories');
    Route::post('/stories_img', [HomeController::class, 'createStoriesImg'])->name('createStoriesImg');
    Route::delete('/stories_text/{id}', [HomeController::class, 'deleteStoriesText'])->name('deleteStoriesText');
    Route::delete('/stories_img/{id}', [HomeController::class, 'deleteStoriesImg'])->name('deleteStoriesImg');


   

    Route::get('/friendRequests/{id}', [FriendController::class,'friendRequests'])->name('listRequestFriend');
    Route::get('/friendList/{id}', [FriendController::class,'friendList'])->name('listFriend');
    Route::post('/approveRequest/{id}', [FriendController::class,'approveRequest'])->name('replyRequest');

    Route::post('/groups', [GroupControllers::class, 'createGroup'])->name('createGroup');
    Route::post('/edit_groups/{id}', [GroupControllers::class, 'editGroup'])->name('editGroup');
    Route::delete('/groups/{id}', [GroupControllers::class, 'deleteGroup'])->name('deleteGroup');
    Route::get('/members/{id}', [GroupControllers::class,'member'])->name('listMemberGroup');
    Route::get('/memberRequests/{id}', [GroupControllers::class,'memberRequests'])->name('listMemberRequest');

    Route::get('/postsgroup/{id}', [GroupControllers::class, 'index'])->name('Group_index');
    Route::post('/groups/{id}', [GroupControllers::class, 'createPost'])->name('createPostGroup');
    Route::post('/groups/{id}/{id2}', [GroupControllers::class, 'updatePost'])->name('updatePostGroup');
    Route::delete('/groups/{id}/{id2}', [GroupControllers::class, 'deletePost'])->name('deletePostGroup');

    Route::post('/join/{id}', [GroupControllers::class, 'join'])->name('sendRequestGroup');
    Route::post('/acpMember/{id}', [GroupControllers::class, 'acpMember'])->name('acpMember');
    Route::post('/refMember/{id}', [GroupControllers::class, 'refMember'])->name('refMember');
    Route::post('/delMember/{id}', [GroupControllers::class, 'delMember'])->name('delMember');
    Route::post('/outGroup/{id}', [GroupControllers::class, 'outgroup'])->name('outgroup');

});
