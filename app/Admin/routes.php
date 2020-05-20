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


    /** 终端品牌管理 **/
    $router->resource('brands', BrandController::class);


    /* 交易类型管理 */
    $router->resource('trade-types', TradeTypeController::class);


    // 文章类型
    $router->resource('article-types', ArticleTypeController::class);
    // 文章列表
    $router->resource('articles', ArticleController::class);


    // 消息通知
    $router->resource('buser-messages', BuserMessageController::class);


    // 商城产品管理
    $router->resource('products', ProductController::class);
    // 商品订单管理
    $router->resource('orders', OrderController::class);

    // 活动政策
    $router->resource('policies', PolicyController::class);
    // 用户政策活动信息
    $router->resource('user-policies', UserPolicyController::class);
});
http://wk.changhuoban.com/admin