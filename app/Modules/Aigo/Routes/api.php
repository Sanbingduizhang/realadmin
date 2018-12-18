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

//Route::get('/aigo', function (Request $request) {
//    // return $request->aigo();
//})->middleware('auth:api');
Route::group(['prefix' => '/aigo/train'],function () {
    Route::get('/test','TrainController@test')->name('aigo.train.test');
});
