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

Route::group(['middleware' => ['checktokenorno'],'prefix' => 'home'], function () {
    //获取cate分类
    Route::get('/cate', 'IndexController@cate')->name('home.cate');
    //首页列表显示
    Route::get('/index', 'IndexController@index')->name('home.index');
    //首页右边两个列表显示
    Route::get('/index-other', 'IndexController@articleOther')->name('home.articleOther');
    //首页获取单个文章的信息
    Route::get('/oneart/{id}', 'IndexController@articleMsg')->name('home.oneart');
    //获取单个article下面的评论
    Route::get('/artcom/{id}', 'IndexController@articleComent')->name('home.artcom');
    //获取单个article下面单个评论的回复
    Route::get('/artreply/{id}', 'IndexController@articleReply')->name('home.artreply');

});
Route::group(['middleware' => ['checktoken'],'prefix' => 'opera'],function () {
    //获取个人未删除作品，在前台页面显示
    Route::get('/my-contents', 'IndexController@myArticles')->name('home.myArticles');
});
