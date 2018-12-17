<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your module. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'base'], function () {
    Route::post('/login', 'BaseController@login');
    Route::post('/register', 'BaseController@register');
    //验证码
    Route::get('/captcha','BaseController@captcha');
    Route::get('/verifyCaptcha','BaseController@verifyCaptcha');
});
