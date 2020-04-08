<?php

use Illuminate\Routing\Router;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('admin.home');

    // 轮播图管理
    $router->resource('plugs', PlugController::class);
    // 用户管理
    $router->resource('busers', BuserController::class);
    // 用户组管理
    $router->resource('user-groups', UserGroupController::class);
    // 提现管理
    $router->resource('withdraws', WithdrawController::class);
    // 分享类型管理
    $router->resource('share-types', ShareTypeController::class);
    // 分享素材管理
    $router->resource('shares', ShareController::class);
    // 商户编号管理
    $router->resource('merchants', MerchantController::class);
    // 交易管理
    $router->resource('trades', TradeController::class);
    // 分润管理
    $router->resource('cashes', CashController::class);
});
