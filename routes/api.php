<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController ;

Route::post('/login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'registration']);

Route::group(['middleware' => 'auth:api'], function () {
    Route::post( 'logout', [ AuthController::class, 'logout' ] );
    Route::get( 'user/profile', [ UserController::class, 'profile' ] );
    Route::post('user/profile/{id}', [UserController::class, 'updateUserProfile']);
});