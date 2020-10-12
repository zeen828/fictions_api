<?php

use Illuminate\Http\Request;

/**
 * 自訂API路由
 */

Route::prefix('cartoon')->group(function () {
    Route::get('test', 'Api\OrdersController@index');
    Route::prefix('pay')->group(function () {
        Route::match(['get', 'post'], 'payNotifyError', 'Api\OrdersController@index');
        Route::match(['get', 'post'], 'payNotifyOrder', 'Api\OrdersController@query');
        Route::match(['get', 'post'],'payNotify', 'Api\OrdersController@payNotify');
    });
});
