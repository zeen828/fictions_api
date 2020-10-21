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

    $router->prefix('management')->group(function(Router $router){
        // 會員
        $router->resource('admin/users', Management\AdminUsersController::class);
    });

    $router->prefix('fictions')->group(function(Router $router){

        // 會員
        $router->resource('users', Fictions\UserController::class);

        // 訂單
        $router->resource('orders', Fictions\OrderController::class);

        // 書籍
        $router->prefix('books')->group(function(Router $router){
            $router->resource('book', Fictions\Books\BookinfoController::class);
            $router->resource('{bookid}/chapters', Fictions\Books\BookchapterController::class);
            $router->resource('chapter', Fictions\Books\BookchapterController::class);
            $router->resource('type', Fictions\Books\BooktypeController::class);
            $router->resource('ranking', Fictions\Books\RankingController::class);
            $router->get('rankingbooks', 'Fictions\Books\RankingBooksController@index');
            $router->post('rankingbooks', 'Fictions\Books\RankingBooksController@store');
        });

        // 支付
        $router->prefix('payments')->group(function(Router $router){
            $router->resource('payment', Fictions\Payments\PaymentController::class);
            $router->resource('amount', Fictions\Payments\AmountController::class);
        });

        // 域名
        $router->resource('domains', Fictions\DomainsController::class);

        // 推廣
        $router->prefix('promotes')->group(function(Router $router){
            $router->resource('channel', Fictions\Promotes\ChannelController::class);
            $router->resource('apk', Fictions\Promotes\ApkController::class);
            $router->resource('sysapk', Fictions\Promotes\ChannelApkController::class);
        });

        // 統計
        $router->prefix('analysis')->group(function(Router $router){
            $router->resource('user', Fictions\Analysis\AnalysisUserController::class);
            $router->resource('channel', Fictions\Analysis\AnalysisChannelController::class);
        });

        // 統計
        $router->prefix('chart')->group(function(Router $router){
            $router->get('user', 'Fictions\Chart\ChartController@user');
            $router->get('book', 'Fictions\Chart\ChartController@book');
            $router->get('channel', 'Fictions\Chart\ChartController@channel');
        });

    });

});
