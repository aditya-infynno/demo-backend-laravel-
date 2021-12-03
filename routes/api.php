<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\DeveloperController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/
Route::post('register',[RegisteredUserController::class,'store']);

Route::post('login',[AuthenticatedSessionController::class,'store']);

Route::post('forgot-password',[PasswordResetLinkController::class,'store']);

Route::post('reset-password',[NewPasswordController::class,'store']);


/*
|--------------------------------------------------------------------------
| Developer Resource Route
|--------------------------------------------------------------------------
*/
Route::post('developer/delete', [DeveloperController::class,'destroyMany'])->middleware('auth:sanctum');
Route::resource('developer', DeveloperController::class)
->middleware('auth:sanctum')
->except(['create','show']);