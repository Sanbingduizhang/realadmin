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
    //删除文章
    Route::post('/ar/del', 'AArticleController@delar')->name('admin.delar');
    //文章的上下架
    Route::post('/ar/sxj', 'AArticleController@sxjar')->name('admin.sxjar');
    //发布内容
    Route::post('/ar/pubar', 'AArticleController@pubar')->name('home.pubar');

    //后台首页显示
    Route::get('/tj/show', 'AHomeController@tjshow')->name('home.tjshow');
});

