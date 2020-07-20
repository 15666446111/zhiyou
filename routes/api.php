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
	 * @version [<用户登录接口>] [<description>]
	 * @return  [<返回用户认证的hash令牌>]
	 * @version [<在所有请求之前请求>] [<所有接口都需使用此接口返回的令牌>]
	 */
    Route::post('/login', 'V1\LoginController@login');

    /**
     * @version [<获取轮播图接口>] [<description>]
     * @return  [<返回显示中的轮播图>]
     * @version [<首页轮播图>] 
     */
	Route::middleware('AuthToken')->get('/plug', 'V1\PlugController@index');

    /**
     * @version [<获取系统公告>] [<description>]
     * @return  [<返回系统公告列表>]
     * @version [<首页轮播图下方的系统公告>] 
     */
    Route::middleware('AuthToken')->get('/notice', 'V1\ArticleController@Notice');

    /**
     * @version [<获取常见问题>] [<description>]
     * @return  [<返回常见问题列表>]
     * @version [<产品使用的常见问题>] 
     */
    Route::middleware('AuthToken')->get('/problem', 'V1\ArticleController@problem');

    /**
     * @version [<获取微信分享文案>] [<description>]
     * @return  [<返回微信分享文案列表>]
     * @version [<微信分享文案列表>] 
     */
    Route::middleware('AuthToken')->get('/wx_share_list', 'V1\ArticleController@wxShare');


    /**
     * @version [<团队扩展分享二维码>] [<description>]
     * @return  [带二维码的分享海报]   [<description>]
     * @version [<分享二维码] [<description>]
     */
    Route::middleware('AuthToken')->get('/team_share', 'V1\ShareController@team');

    /**
     * @version [<团队扩展分享推广用户二维码>] [<description>]
     * @return  [带二维码的推广用户分享海报]   [<description>]
     * @version [<分享二维码] [<description>]
     */
    Route::middleware('AuthToken')->get('/user_share', 'V1\ShareController@extendUser');

    /**
     * @version [<团队扩展分享二维码>] [<description>]
     * @return  [带二维码的分享海报]   [<description>]
     * @version [<分享二维码] [<description>]
     */
    Route::middleware('AuthToken')->get('/merchant_share', 'V1\ShareController@merchant');

    /**
     * @version [<商户推荐海报>] [<description>]
     * @return  [生产商户推荐海报， 扫码填写表单]   [<description>]
     * @version [<分享二维码] [<description>]
     */
    Route::middleware('AuthToken')->get('/temail_share', 'V1\ShareController@extendTemail');


    /**
     * @version [<APP 首页 伙伴管理>] [<description>]
     * @return  [首页的伙伴管理直接下级列表]   [<description>]
     * @version [<伙伴管理] [<description>]
     */
    Route::middleware('AuthToken')->get('/my_team', 'V1\TeamController@index');

    /**
     * @version [<APP 首页 统计信息>] [<description>]
     * @return  [返回 首页中间模块统计信息]   [<description>]
     * @version [<统计信息] [<description>]
     */
    Route::middleware('AuthToken')->get('/index_info', 'V1\IndexController@info');


    /**
     * @version [<APP 团队数据>] [<description>]
     * @return  [团队栏位 团队首页统计数据 日 月 总]   [<description>]
     * @version [<团队首页统计数据] [<description>]
     */
    Route::middleware('AuthToken')->get('/team_data', 'V1\TeamController@data');



    /**
     * @version [<APP 我的栏位>] [<description>]
     * @return  [个人信息 获取个人信息]   [<description>]
     * @version [<个人信息接口] [<description>]
     */
    Route::middleware('AuthToken')->get('/mine', 'V1\MineController@info');



    /**
     * @version [<APP 获取用户伙伴信息>] [<description>]
     * @return  [个人信息 获取伙伴信息]   [<description>]
     * @version [<伙伴信息接口] [<description>]
     */
    Route::middleware('AuthToken')->post('/userInfo', 'V1\MineController@userInfo');



    /**
     * @version [<APP 提现记录>] [<description>]
     * @return  [个人信息 获取提现记录]   [<description>]
     * @version [<提现记录信息接口] [<description>]
     */
    Route::middleware('AuthToken')->get('/draw', 'V1\MineController@draw_log');



    /**
     * @version [<APP 获取消息通知>] [<description>]
     * @return  [获取发送的消息接口]   [<description>]
     * @version [<消息通知信息接口] [<description>]
     */
    Route::middleware('AuthToken')->get('/message', 'V1\MessageController@getMessage');


    /**
     * @version [<APP 获取产品分类接口>] [<description>]
     * @return  [获取正在展示的产品分类]   [<description>]
     * @version [<产品分类信息接口] [<description>]
     */
    Route::middleware('AuthToken')->get('/getproducttype', 'V1\ProductController@getType');

    /**
     * @version [<APP 获取产品列表接口>] [<description>]
     * @return  [获取正在展示的产品列表]   [<description>]
     * @version [<产品列表信息接口] [<description>]
     */
    Route::middleware('AuthToken')->get('/getproduct', 'V1\ProductController@getProduct');

    /**
     * @version [<APP 获取产品信息接口>] [<description>]
     * @return  [获取单独某个产品信息]   [<description>]
     * @version [<产品信息接口] [<description>]
     */
    Route::middleware('AuthToken')->get('/getproductinfo', 'V1\ProductController@getProductInfo');


    /**
     * @version [<APP 商户登记>] [<description>]
     * @return  [获取该用户下所有未登记的机器]   [<description>]
     * @version [<未登记机器获取接口] [<description>]
     */
    Route::middleware('AuthToken')->get('/getNoBindMerchant', 'V1\MerchantController@getNoBindList');


    /**
     * @version [<vector>] [< 分红奖励页面 >]
     */
    Route::middleware('AuthToken')->get('/bonusPage', 'V1\BonusController@page');


    /**
     * @version [<APP 获取政策活动列表>] [<description>]
     * @return  [获取平台所有的政策活动]   [<description>]
     * @version [<获取政策后的] [<description>]
     */
    Route::middleware('AuthToken')->get('/getPolicy', 'V1\PolicyController@getPolicy');



    /**
     * @version [<APP 获取政策活动列表>] [<description>]
     * @return  [获取平台所有的政策活动]   [<description>]
     * @version [<获取政策后的] [<description>]
     */
    Route::middleware('AuthToken')->post('/getPolicyInfo', 'V1\PolicyController@getPolicyInfo');

    /**
     * @version [<APP 获取政策活动列表>] [<description>]
     * @return  [获取平台所有的政策活动]   [<description>]
     * @version [<获取政策后的] [<description>]
     */
    Route::middleware('AuthToken')->post('/setPolicyInfo', 'V1\PolicyController@setPolicyInfo');


    /**
     * @version [<APP 获取政策活动列表>] [<description>]
     * @return  [获取平台所有的政策活动]   [<description>]
     * @version [<获取政策后的] [<description>]
     */
    Route::middleware('AuthToken')->post('/getTradeDetail', 'V1\TradeController@getDetail');


    /**
     * @version [<APP 获取机具活动详情>] [<description>]
     * @return  [用户发在朋友圈的推荐二维码 被朋友扫码申请后的信息]   [<description>]
     * @version [< ] [<description>]
     */
    Route::middleware('AuthToken')->get('/getApplyFirend', 'V1\ApplicationController@list');

    /**
     * @version [<APP 获取机具活动详情>] [<description>]
     * @return  [用户发在朋友圈的推荐二维码 被朋友扫码申请后的信息]   [<description>]
     * @version [< ] [<description>]
     */
    Route::middleware('AuthToken')->get('/setApplyFirend', 'V1\ApplicationController@set');

    /**
     * @version [<APP 获取朋友圈申请的机器列表 商户推荐列表>] [<description>]
     * @return  [机具的达标返现与交易情况]   [<description>]
     * @version [<机具的达标返现情况] [<description>]
     */
    Route::middleware('AuthToken')->get('/getTerminalActiveDetail', 'V1\TerminalController@getActiveDetail');


    /**
     * @version [<APP 获取某代理的商户分布情况 >] [<description>]
     * @return  [代理的商户分布情况]   [<description>]
     * @version [<获取某代理的商户分布情况] [<description>]
     */
    Route::middleware('AuthToken')->get('/getAgentMerchant', 'V1\AgentController@getAgentDetail');
    Route::middleware('AuthToken')->get('/getAgentTeam',     'V1\AgentController@getAgentTeamDetail');
    Route::middleware('AuthToken')->get('/getAgentTemail',   'V1\AgentController@getAgentTemail');
    Route::middleware('AuthToken')->get('/getAgentActive',   'V1\AgentController@getAgentActive');


    /**
     * @version [<vector>] [< 添加用户收货地址接口 >]
     */
    Route::middleware('AuthToken')->post('/addressAdd', 'V1\AddressController@address');


    /**
     * @version [<vector>] [< 查询用户收货地址接口 >]
     */
    Route::middleware('AuthToken')->get('/getAddress', 'V1\AddressController@getAAddress');


     /**
     * @version [<vector>] [< 删除用户收货地址接口 >]
     */
    Route::middleware('AuthToken')->get('/deAddress', 'V1\AddressController@deleteAddress');
 

    /**
     * @version [<vector>] [< 修改用户收货地址接口 >]
     */
    Route::middleware('AuthToken')->get('/upAddress', 'V1\AddressController@updateAddress');



    /**
     * @version [<vector>] [< 查询单个收货地址接口 >]
     */
    Route::middleware('AuthToken')->get('/getFirstAddress', 'V1\AddressController@firstAddress');    


    
    /**
     * @version [<vector>] [< 查询默认收货地址接口 >]
     */
    Route::middleware('AuthToken')->get('/getDefaultAddress', 'V1\AddressController@defaultAddress');    



    /**
     * 收益页面接口
     */
    Route::middleware('AuthToken')->get('/cashs', 'V1\CashsController@cashsIndex');

    
    /**
     * 首页商户登记绑定接口
     */
    Route::middleware('AuthToken')->get('/register', 'V1\MerchantsController@registers');

    
    /**
     * 商户列表接口
     */
    Route::middleware('AuthToken')->get('/getMerchantsList', 'V1\MerchantsController@merchantsList');


     /**
     * 个人商户详情接口
     */
    Route::middleware('AuthToken')->get('/getMerchantInfo', 'V1\MerchantsController@merchantInfo');


    /**
     * 修改个人登录密码接口
     */
    Route::middleware('AuthToken')->get('/setUserPwd', 'V1\SetUserController@updatePwd');


    
    /**
     * 添加银行卡结算信息接口
     */
    Route::middleware('AuthToken')->post('/createBank', 'V1\SetUserController@insertBank');
    

    /**
     * 查询银行卡结算信息接口
     */
    Route::middleware('AuthToken')->get('/getBankInfo', 'V1\SetUserController@selectBank');


    /**
     * 查询默认银行卡信息接口
     */
    Route::middleware('AuthToken')->get('/getBankDefault', 'V1\SetUserController@bankDefault');


    /**
     * 查询单个银行卡信息接口
     */
    Route::middleware('AuthToken')->get('/getBankFirst', 'V1\SetUserController@bankFirst');

    
    /**
     * 删除银行卡结算信息接口
     */
    Route::middleware('AuthToken')->get('/deBank', 'V1\SetUserController@unsetBank');


    /**
     * 修改银行卡结算信息接口
     */
    Route::middleware('AuthToken')->get('/upBank', 'V1\SetUserController@updateBank');


    /**
     * 用户提现接口
     */
    Route::middleware('AuthToken')->post('/getWithdrawal', 'V1\SetUserController@Withdrawal');


    /**
     * 生成订单接口
     */
    Route::middleware('AuthToken')->post('/addOrderCreate', 'V1\OrdersController@orderCreate');


    /**
     * 查询订单接口
     */
    Route::middleware('AuthToken')->get('/getOrderUser', 'V1\OrdersController@getOrder');


    /**
     * 机具管理页面接口
     */
    Route::middleware('AuthToken')->get('/getBindAll', 'V1\MerchantController@getBind');


    /**
     * 提现税点接口
     */
    Route::middleware('AuthToken')->get('/getPoint', 'V1\SetUserController@point');


    /**
     * 文章详情接口
     */
    Route::middleware('AuthToken')->get('/getArticle', 'V1\ArticleController@Article');


    /**
     * 机具详情接口
     */
    Route::middleware('AuthToken')->get('/getTail', 'V1\MerchantTailController@getMerchantsTail');

    
    /**
     * 查询用户未绑定终端机器
     */
    Route::middleware('AuthToken')->get('/getUnBoundInfo', 'V1\TransferController@getUnBound');
    

    /**
     * 划拨
     */
    Route::middleware('AuthToken')->post('/addTransfer', 'V1\TransferController@transfer');


    /**
     * 回拨机器列表
     */
    Route::middleware('AuthToken')->get('/getBackList', 'V1\TransferController@backList');


    /**
     * 回拨
     */
    Route::middleware('AuthToken')->post('/addBackTransfer', 'V1\TransferController@backTransfer');

    
    /**
     * @version [<vector>] [< 划拨回拨记录 >]
     */
    Route::middleware('AuthToken')->get('/getTransferLog', 'V1\TransferController@transferLog');


    /**
     * @version [<vector>] [< 查询商户交易明细 >]
     */
    Route::middleware('AuthToken')->get('/getMerchantDetails', 'V1\MerchantsController@MerchantDetails');


    /**
     * @version 忘记密码接口
     */
    Route::post('/forgetPwd', 'V1\LoginController@forget');







    /**
     * @version [<vector>] [< 团队-> 业务详情 -> 详细数据]
     */
    Route::middleware('AuthToken')->get('/getTeamTradeDetail', 'V1\DetailController@TradeDetail');  // 团队-业务详情-交易量

});



Route::fallback(function(){ 

    return response()->json(['error'=>['message' => 'Request Error!']], 404);

});

