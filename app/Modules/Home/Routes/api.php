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

Route::get('/home', function (Request $request) {
    // return $request->home();
})->middleware('auth:api');

Route::group(['middleware' => ['checktoken'],'prefix' => 'home'],function () {
    Route::get('/index','IndexController@index')->name('home.index');
});
