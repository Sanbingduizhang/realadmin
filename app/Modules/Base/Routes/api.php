<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your module. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
//getUsers
//Route::get('/base', function (Request $request) {
//    // return $request->base();
//})->middleware('auth:api');
Route::group(['middleware' => ['checktoken'],'prefix' => 'opera'],function () {
    //获取用户个人数据
    Route::get('/getuser', 'UserController@getUsers')->name('opera.getUsers');
});