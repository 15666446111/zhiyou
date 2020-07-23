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

Route::middleware('auth:api')->get('/user', function (Request $request) {

    return $request->user();

});


/**
 * @author  [Pudding]  [<755969423@qq.com>]
 * @version [<API路由的1.0版本>] [<description>]
 */
Route::prefix('V1')->group(function () {

    /**
     * @author  [ Gong Ke] [< 755969423@qq.com >]
     * @version [<vector>] [< 用户登陆 >]
     */
    Route::post('/login',       'V1\LoginController@login');              // 用户登陆
    Route::post('/forgetPwd',   'V1\LoginController@forget');             // 忘记密码
    Route::post('/getCode',     'V1\LoginController@code');               // 发送验证码


    /**
     * @author  [ Gong Ke] [< 755969423@qq.com >]
     * @version [<vector>] [< 首页信息 >]
     */
    Route::middleware('AuthToken')->get('/plug', 'V1\PlugController@index');        // 首页 - 轮播图
    Route::middleware('AuthToken')->get('/index_info', 'V1\IndexController@info');  // 首页 - 信息统计
    Route::middleware('AuthToken')->get('/bonusPage', 'V1\BonusController@page');   // 首页 - 分红奖励

    /**
     * @author  [ Gong Ke] [< 755969423@qq.com >]
     * @version [<vector>] [< 首页 - 分享海报 >]
     */
    Route::middleware('AuthToken')->get('/team_share',      'V1\ShareController@team');              //  首页 - 扩展代理
    Route::middleware('AuthToken')->get('/user_share',      'V1\ShareController@extendUser');        //  首页 - 扩展用户
    Route::middleware('AuthToken')->get('/merchant_share',  'V1\ShareController@merchant');          //  首页 - 商户注册
    Route::middleware('AuthToken')->get('/temail_share',    'V1\ShareController@extendTemail');      //  我的 - 机器推荐(朋友圈)

    /**
     * @author  [ Gong Ke] [< 755969423@qq.com >]
     * @version [<vector>] [< 首页 - 文章类型 >]
     */
    Route::middleware('AuthToken')->get('/notice',          'V1\ArticleController@Notice');         // 首页 - 系统公告
    Route::middleware('AuthToken')->get('/problem',         'V1\ArticleController@problem');        // 首页 - 常见问题
    Route::middleware('AuthToken')->get('/wx_share_list',   'V1\ArticleController@wxShare');        // 我的 - 微信分享文案
    Route::middleware('AuthToken')->get('/getArticle',      'V1\ArticleController@Article');        // 获取文章详情

    /**
     * @author  [ Gong Ke] [< 755969423@qq.com >]
     * @version [<vector>] [< 首页 - 商户登记 >]
     */
    Route::middleware('AuthToken')->get('/getNoBindMerchant', 'V1\MerchantController@getNoBindList');        // 首页 - 商户登记 - 未绑定列表
    Route::middleware('AuthToken')->get('/register',          'V1\MerchantsController@registers');           // 首页 - 商户登记 - 提交登记信息


    /**
     * @author  [ Gong Ke] [< 755969423@qq.com >]
     * @version [<vector>] [< 首页 - 商户管理  >]
     */
    Route::middleware('AuthToken')->get('/getMerchantsList',   'V1\MerchantsController@merchantsList');         // 商户管理 - 商户列表
    Route::middleware('AuthToken')->get('/getMerchantsInfo',   'V1\MerchantsController@merchantInfo');          // 商户管理 - 商户详情
    Route::middleware('AuthToken')->get('/getMerchantsPolicy', 'V1\MerchantsController@merchantPolicy');        // 商户管理 - 商户详情 - 活动信息
    Route::middleware('AuthToken')->get('/getMerchantsDetails','V1\MerchantsController@MerchantDetails');       // 商户管理 - 商户详情 - 交易明细
    

    /**
     * @author  [ Gong Ke] [< 755969423@qq.com >]
     * @version [<vector>] [< 首页 - 伙伴管理  >]
     */
    Route::middleware('AuthToken')->get('/my_team', 'V1\TeamController@index');                                 // 首页 - 伙伴管理 - 伙伴列表
    Route::middleware('AuthToken')->post('/userInfo', 'V1\MineController@userInfo');                            // 首页 - 伙伴管理 - 伙伴详情
    Route::middleware('AuthToken')->post('/getPolicyInfo', 'V1\PolicyController@getPolicyInfo');                // 首页 - 伙伴管理 - 政策列表
    Route::middleware('AuthToken')->post('/setPolicyInfo', 'V1\PolicyController@setPolicyInfo');                // 首页 - 伙伴管理 - 设置政策




    /**
     * @author  [ Gong Ke] [< 755969423@qq.com >]
     * @version [<vector>] [< 我的 - 我的栏位  >]
     */
    Route::middleware('AuthToken')->get('/mine', 'V1\MineController@info');                                     // 我的 - 获取个人信息
    Route::middleware('AuthToken')->get('/message', 'V1\MessageController@getMessage');                         // 我的 - 我的消息列表
    Route::middleware('AuthToken')->get('/setUserPwd', 'V1\SetUserController@updatePwd');                       // 我的 - 设置 - 修改个人密码



    /**
     * @author  [ Gong Ke] [< 755969423@qq.com >]
     * @version [<vector>] [< 我的 - 提现相关  >]
     */
    Route::middleware('AuthToken')->get('/getPoint', 'V1\SetUserController@point');                             // 我的 - 提现 - 获取提现税点
    Route::middleware('AuthToken')->post('/getWithdrawal', 'V1\SetUserController@Withdrawal');                  // 我的 - 提现 - 申请提现
    Route::middleware('AuthToken')->get('/draw', 'V1\MineController@draw_log');                                 // 我的 - 提现 - 提现记录列表



    /**
     * @author  [ Gong Ke] [< 755969423@qq.com >]
     * @version [<vector>] [< 首页 - 商城  >]
     */
    Route::middleware('AuthToken')->get('/getproducttype', 'V1\ProductController@getType');                     // 首页 - 商城 - 获取产品分类
    Route::middleware('AuthToken')->get('/getproduct', 'V1\ProductController@getProduct');                      // 首页 - 商城 - 获取产品列表
    Route::middleware('AuthToken')->get('/getproductinfo', 'V1\ProductController@getProductInfo');              // 首页 - 商城 - 获取产品详情
    Route::middleware('AuthToken')->post('/addOrderCreate', 'V1\OrdersController@orderCreate');                 // 首页 - 商城 - 生成订单信息
    Route::middleware('AuthToken')->get('/getOrderUser', 'V1\OrdersController@getOrder');                       // 我的 - 订单 - 获取订单信息


    /**
     * @author  [ Gong Ke] [< 755969423@qq.com >]
     * @version [<vector>] [< 我的 - 机具管理  >]
     */
    Route::middleware('AuthToken')->get('/getBindAll', 'V1\MerchantController@getBind');                        // 我的 - 机具管理 - 获取绑定的机具
    Route::middleware('AuthToken')->get('/getTail', 'V1\MerchantTailController@getMerchantsTail');              // 我的 - 机具管理 - 获取机具详情
    Route::middleware('AuthToken')->get('/getUnBoundInfo', 'V1\TransferController@getUnBound');                 // 我的 - 机具管理 - 未绑定的机具
    Route::middleware('AuthToken')->post('/addTransfer', 'V1\TransferController@transfer');                     // 我的 - 机具管理 - 机具划拨
    Route::middleware('AuthToken')->get('/getBackList', 'V1\TransferController@backList');                      // 我的 - 机具管理 - 机具回拨列表
    Route::middleware('AuthToken')->post('/addBackTransfer', 'V1\TransferController@backTransfer');             // 我的 - 机具管理 - 机具回拨
    Route::middleware('AuthToken')->get('/getTransferLog', 'V1\TransferController@transferLog');                // 我的 - 机具管理 - 划拨回拨记录
    Route::middleware('AuthToken')->get('/getPolicy', 'V1\PolicyController@getPolicy');                         // 我的 - 机具管理 - 政策活动选择


    /**
     * @author  [ Gong Ke] [< 755969423@qq.com >]
     * @version [<vector>] [< 我的 - 推广商户 - 获取我的推荐机具  >]
     */
    Route::middleware('AuthToken')->get('/getApplyFirend', 'V1\ApplicationController@list');                // 我的 - 商户推荐 - 获取推荐列表
    Route::middleware('AuthToken')->get('/setApplyFirend', 'V1\ApplicationController@set');                 // 我的 - 商户推荐 - 设置推荐机具


    /**
     * @author  [ Gong Ke] [< 755969423@qq.com >]
     * @version [<vector>] [< 我的 - 收货地址 >]
     */
    Route::middleware('AuthToken')->post('/addressAdd',         'V1\AddressController@address');            // 我的 - 添加收货地址信息
    Route::middleware('AuthToken')->get('/getAddress',          'V1\AddressController@getAAddress');        // 我的 - 获取收货地址列表
    Route::middleware('AuthToken')->get('/deAddress',           'V1\AddressController@deleteAddress');      // 我的 - 删除用户收货地址
    Route::middleware('AuthToken')->get('/upAddress',           'V1\AddressController@updateAddress');      // 我的 - 修改用户收货地址
    Route::middleware('AuthToken')->get('/getFirstAddress',     'V1\AddressController@firstAddress');       // 我的 - 查询单个收货地址   
    Route::middleware('AuthToken')->get('/getDefaultAddress',   'V1\AddressController@defaultAddress');     // 我的 - 查询默认收货地址


    /**
     * @author  [ Gong Ke] [< 755969423@qq.com >]
     * @version [<vector>] [< 我的 - 结算卡信息管理  >]
     */
    Route::middleware('AuthToken')->post('/createBank',     'V1\SetUserController@insertBank');         // 我的 - 添加银行卡结算信息
    Route::middleware('AuthToken')->get('/getBankInfo',     'V1\SetUserController@selectBank');         // 我的 - 获取银行卡列表信息
    Route::middleware('AuthToken')->get('/getBankDefault',  'V1\SetUserController@bankDefault');        // 我的 - 查询默认银行卡信息
    Route::middleware('AuthToken')->get('/getBankFirst',    'V1\SetUserController@bankFirst');          // 我的 - 查询单个银行卡信息
    Route::middleware('AuthToken')->get('/deBank',          'V1\SetUserController@unsetBank');          // 我的 - 删除银行卡结算信息
    Route::middleware('AuthToken')->get('/upBank',          'V1\SetUserController@updateBank');         // 我的 - 修改银行卡结算信息


    /**
     * @author  [ Gong Ke] [< 755969423@qq.com >]
     * @version [<vector>] [<  收益 - 收益栏目  >]
     */
    Route::middleware('AuthToken')->get('/cashs', 'V1\CashsController@cashsIndex');                     // 收益信息


    /**
     * @author  [ Gong Ke] [< 755969423@qq.com >]
     * @version [<vector>] [< 团队-> 业务详情 -> 详细数据]
     */
    Route::middleware('AuthToken')->get('/team_data',          'V1\TeamController@data');           // 团队栏目主页
    Route::middleware('AuthToken')->post('/getTradeDetail',    'V1\TradeController@getDetail');     // 团队-业务详情
    Route::middleware('AuthToken')->get('/getTeamTradeDetail', 'V1\DetailController@TradeDetail');  // 团队-业务详情-交易量
    Route::middleware('AuthToken')->get('/getAgentActive',     'V1\DetailController@AgentActive');  // 团队-业务详情-激活数据
    Route::middleware('AuthToken')->get('/getAgentTemail',     'V1\DetailController@AgentDetail');  // 团队-业务详情-机器总数
    Route::middleware('AuthToken')->get('/getCashDetail',      'V1\DetailController@CashDetail');
    Route::middleware('AuthToken')->get('/getAgentTeam',       'V1\DetailController@TeamDetail');   // 团队-业务详情-团队数据
    //Route::middleware('AuthToken')->get('/getAgentMerchant',   'V1\DetailController@MercDetail');  // 团队-业务详情-商户数据
    Route::middleware('AuthToken')->get('/getAvgTemail',       'V1\DetailController@AvgDetail');    // 团队-业务详情-台均数据

});


Route::fallback(function(){ 

    return response()->json(['error'=>['message' => 'Request Error!']], 404);

});

