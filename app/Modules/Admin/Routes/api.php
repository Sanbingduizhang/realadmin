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

//Route::get('/admin', function (Request $request) {
//    // return $request->admin();
//})->middleware('auth:api');
Route::group(['middleware' => ['checktoken'],'prefix' => 'admin'],function () {
    //发布内容
    Route::get('/index', 'AArticleController@index')->name('admin.index');
});

