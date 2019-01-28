<?php

use Illuminate\Http\Request;
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

//Route::get('/wechat', function (Request $request) {
//    // return $request->wechat();
//})->middleware('auth:api');
Route::any('/wechat','WechatController@server');
Route::any('/set-btn','WechatController@setButton');

Route::group(['middleware' => ['wechat.oauth']], function () {
    Route::any('/bind-user','WxController@bindUser');
});

//Route::group(['prefix' => 'wx','middleware' => ['wechat.oauth:snsapi_userinfo']],function () {
Route::group(['prefix' => 'wx'],function () {
   Route::any('/','WxController@server');

   Route::any('/bind-set','WxController@bindSet');
   Route::any('/set-btn','WxController@setButton');
});