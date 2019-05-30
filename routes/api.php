<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/boss', 'BossController@store');
Route::post('/boss/login', 'BossController@login');
Route::middleware('bossidentify')->group(function() {
    Route::get('/boss/{boss}', 'BossController@show');
    Route::patch('/boss/{boss}', 'BossController@update');
    Route::delete('/boss/logout/{boss}', 'BossController@logout');
    Route::post('/groups', 'GroupController@store');
});
