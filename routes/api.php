<?php

use App\Http\Controllers\Admin\UserController;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::group(['prefix' => 'user', 'middleware' => ['auth:sanctum']], function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/', function (Request $request) {
        $user = $request->user();
        $responseData = [
            'status' => 200,
            'success' => true,
            'message' => 'success',
            'data' => ['user' => $user]
        ];
        return response()->json($responseData);
    });
    Route::get('allusers', [UserController::class, 'allusers']);
    Route::get('roles', [UserController::class, 'roles']);
    Route::post('create-new-user', [UserController::class, 'createNewUser']);
    Route::post('change-role-user/{id}', [UserController::class, 'changeRoleUser']);
    Route::post('admin-change-user-pass/{id}', [UserController::class, 'adminChangePasswordUser']);
    Route::post('update', [UserController::class, 'updateUser']);
    Route::post('change-password', [UserController::class, 'change_password']);
    Route::post('create-post', [PostsController::class, 'create_post']);
    Route::get('allposts', [PostsController::class, 'all_post']);
    Route::post('create_comment/{postId}', [CommentController::class, 'create_comment']);
    // Route::post('get_all_comments/{postId}', [CommentController::class, 'get_all_comments']);
    Route::get('getAllComments', [CommentController::class, 'getAllComments']);
});
