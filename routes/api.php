<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\SignupController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\UserController;

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

Route::group(['prefix' => 'v1'], function () {
    Route::post('signup', [SignupController::class, 'signup']);
    Route::post('login', [LoginController::class, 'login']);

    Route::group(['middleware' => 'auth:api'], function () {
        Route::get('role', [UserController::class, 'roles']);

        // user
        Route::get('user', [UserController::class, 'index']);
        Route::get('user/{user}', [UserController::class, 'show']);
        Route::put('user/{user}', [UserController::class, 'update']);
        Route::delete('user/{user}', [UserController::class, 'delete']);
        Route::post('user', [UserController::class, 'store']);

        // auth
        Route::post('logout', [LoginController::class, 'logout']);

        // message
        Route::group(['prefix' => 'message'], function () {
            Route::get('/', [ChatController::class, 'fetchMessages']);
            Route::post('/', [ChatController::class, 'sendMessage']);
            Route::get('user', [ChatController::class, 'user']);
        });
    });
});
