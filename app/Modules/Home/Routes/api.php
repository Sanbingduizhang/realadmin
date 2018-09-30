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

// Route::get('/home', function (Request $request) {
//     // return $request->home();
// })->middleware('auth:api');

Route::group(['prefix' => 'home'], function () {
    //获取cate分类
    Route::get('/cate', 'IndexController@cate')->name('home.cate');
    //首页列表显示
    Route::get('/index', 'IndexController@index')->name('home.index');
    //首页右边两个列表显示
    Route::get('/index-other', 'IndexController@articleOther')->name('home.articleOther');

});
Route::group(['middleware' => ['checktoken'],'prefix' => 'opera'],function () {
    //发布内容
    Route::post('/pub-text', 'ArticleController@pubText')->name('home.pubText');
    //获取个人未删除作品，在前台页面显示
    Route::get('/my-contents', 'IndexController@myArticles')->name('home.myArticles');
});
