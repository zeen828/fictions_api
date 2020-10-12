<?php

use Illuminate\Routing\Router;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
    'as'            => config('admin.route.prefix') . '.',
], function (Router $router) {

    //$router->get('/', 'HomeController@index')->name('home');
    $router->get('/', 'HomeController@home')->name('home');
    //$router->get('/', 'HomeController@chart')->name('chart');
    $router->get('/abcdefg', 'TestsController@index');

    $router->prefix('fictions')->group(function(Router $router){

        // 會員
        $router->resource('users', UserController::class);

        // 訂單
        $router->resource('orders', OrderController::class);

        // 書籍
        $router->prefix('books')->group(function(Router $router){
            $router->resource('book', BookinfoController::class);
            $router->resource('{bookid}/chapters', BookchapterController::class);
            $router->resource('chapter', BookchapterController::class);
            $router->resource('type', BooktypeController::class);
            $router->resource('ranking', RankingController::class);
        });

        // 支付
        $router->prefix('payments')->group(function(Router $router){
            $router->resource('payment', PaymentController::class);
            $router->resource('amount', AmountController::class);
        });

        // 域名
        $router->resource('domains', DomainsController::class);

        // 推廣
        $router->prefix('promotes')->group(function(Router $router){
            $router->resource('channel', ChannelController::class);
            $router->resource('apk', ApkController::class);
            $router->resource('sysapk', ChannelApkController::class);
        });

        // 統計
        $router->prefix('analysis')->group(function(Router $router){
            $router->resource('user', AnalysisUserController::class);
            $router->resource('channel', AnalysisChannelController::class);
        });

        // 統計
        $router->prefix('chart')->group(function(Router $router){
            $router->get('user', 'ChartController@user');
            $router->get('book', 'ChartController@book');
            $router->get('channel', 'ChartController@channel');
        });

    });

});
