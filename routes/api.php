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
Route::get('/groups', 'GroupController@index');
Route::middleware('bossidentify')->group(function() {
    Route::get('/boss/{boss}', 'BossController@show');
    Route::patch('/boss/{boss}', 'BossController@update');
    Route::delete('/boss/logout/{boss}', 'BossController@logout');
    Route::post('/groups', 'GroupController@store');
    Route::patch('/groups/{group}', 'GroupController@update');
    Route::delete('/groups/{group}', 'GroupController@destroy');
    Route::post('/menus', 'MenuController@store');
    Route::post('/flavors', 'MenuController@flavorstore');
    Route::get('/menus', 'MenuController@index');
    Route::get('/dailymenu', 'MenuController@show');
    Route::patch('/menus/{menu}', 'MenuController@menuUpdate');
    Route::patch('/flavors/{flavor}', 'MenuController@flavorUpdate');
    Route::delete('/menus/{menu}', 'MenuController@menuDestroy');
    Route::delete('/flavors/{flavor}', 'MenuController@flavorDestroy');
    Route::post('/deadline', 'DeadlineController@timeStore');
    Route::get('/deadline', 'DeadlineController@timeIndex');
    Route::patch('/deadline/{deadline}', 'DeadlineController@timeUpdate');
    Route::delete('/deadline/{deadline}', 'DeadlineController@timeDestroy');
});
Route::post('/member', 'MemberController@store');
Route::post('/member/login', 'MemberController@login');
Route::middleware('memberidentify')->group(function() {
    Route::patch('/member/{member}', 'MemberController@update');
    Route::delete('/member/logout/{member}', 'MemberController@logout');
});
