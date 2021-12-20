<?php

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

Route::post('register', 'Api\AuthController@register');
Route::post('login', 'Api\AuthController@login')->name('login');

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('service', 'Api\BengkelController@index');
    Route::get('service/{id}', 'Api\BengkelController@show');
    Route::post('service', 'Api\BengkelController@store');
    Route::put('service/{id}', 'Api\BengkelController@update');
    Route::delete('service/{id}', 'Api\BengkelController@destroy');

    Route::put('user', 'Api\UserController@update');
    Route::get('logout', 'Api\AuthController@logout');
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

