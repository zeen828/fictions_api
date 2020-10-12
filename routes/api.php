<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('v1')->group(function () {
    Route::get('test', 'Api\OrdersController@index');
    Route::prefix('order')->group(function () {
        Route::match(['get', 'post'], 'query', 'Api\OrdersController@query');
        Route::match(['get', 'post'],'payNotify', 'Api\OrdersController@payNotify');
    });
    Route::prefix('domain')->group(function () {
        Route::match(['get', 'post'], 'species/{species_id}', 'Api\DomainsController@species');
    });
    Route::prefix('ajax')->group(function () {
        Route::match(['get', 'post'], 'package/{channel_id}/{apk_id}', 'Api\AjaxController@package');
    });
});
