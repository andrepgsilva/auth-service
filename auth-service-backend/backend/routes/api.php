<?php

use App\Http\Controllers\API\AuthController;
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

Route::middleware('auth.api')->get('/user', function (Request $request) {
    return auth()->user();
});

Route::post('/auth/login', [AuthController::class, 'login']);

Route::post('/auth/refresh-token', [AuthController::class, 'refreshToken']);