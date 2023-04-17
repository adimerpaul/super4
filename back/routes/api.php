<?php

use Illuminate\Http\Request;
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

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});
Route::post('sociallogin/{provider}', [\App\Http\Controllers\AuthController::class,'SocialSignup']);
Route::apiResource('rubros', \App\Http\Controllers\RubroController::class);
Route::apiResource('productos', \App\Http\Controllers\ProductoController::class);
