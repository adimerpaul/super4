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
Route::post('/register',[\App\Http\Controllers\UserController::class,'register']);
Route::post('/login',[\App\Http\Controllers\UserController::class,'login']);
Route::post('sociallogin/{provider}', [\App\Http\Controllers\AuthController::class,'SocialSignup']);
Route::apiResource('rubros', \App\Http\Controllers\RubroController::class);
Route::apiResource('productos', \App\Http\Controllers\ProductoController::class);
Route::group(['middleware'=>'auth:sanctum'],function (){
    Route::post('/me', [\App\Http\Controllers\UserController::class,'me']);
    Route::post('/logout', [\App\Http\Controllers\UserController::class,'logout']);
    Route::resource('/user',\App\Http\Controllers\UserController::class);
    Route::resource('/address',\App\Http\Controllers\AddressController::class);
    Route::resource('/invoices',\App\Http\Controllers\InvoiceController::class);
});
