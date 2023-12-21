<?php

use App\Http\Controllers\Admin\UserController;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\AuthController;
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
});
