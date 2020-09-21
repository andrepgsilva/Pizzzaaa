<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ProductsController;
use App\Http\Controllers\API\Auth\LoginController;
use App\Http\Controllers\API\Auth\RegisterController;
use App\Http\Controllers\API\Auth\ForgotPasswordController;

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



Route::post('/login', [LoginController::class, 'authenticate']);
Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth:sanctum');

Route::post('/register', [RegisterController::class, 'register']);
Route::get('/products', [ProductsController::class, 'index']);
Route::post('/password/email', [ForgotPasswordController::class, 'forgot']);
Route::post('/password/reset', [ForgotPasswordController::class, 'reset']);


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

