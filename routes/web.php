<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    //return view('welcome');
    return App::abort(404);
});

// Route::prefix('2/cartoon')->group(function () {
//     Route::prefix('pay')->group(function () {
//         Route::match(['get', 'post'], 'payNotifyOrder', 'Api\OrdersController@query');
//         Route::match(['get', 'post'],'payNotify', 'Api\OrdersController@payNotify');
//     });
// });

// 測試頁
Route::get('tests/index/{no}', 'TestsController@index');
Route::get('tests/wtdb/{no}', 'TestsController@wtdb');
Route::get('tests/model/{no}', 'TestsController@model');
Route::get('tests/oss/{no}', 'TestsController@oss');
Route::get('tests/redi/{no}', 'TestsController@redi');
Route::get('tests/file/{no}', 'TestsController@files');
