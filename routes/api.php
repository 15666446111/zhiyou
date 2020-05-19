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
     * @version [<团队扩展分享二维码>] [<description>]
     * @return  [带二维码的分享海报]   [<description>]
     * @version [<分享二维码] [<description>]
     */
    Route::middleware('AuthToken')->get('/team_share', 'V1\ShareController@team');

    /**
     * @version [<团队扩展分享二维码>] [<description>]
     * @return  [带二维码的分享海报]   [<description>]
     * @version [<分享二维码] [<description>]
     */
    Route::middleware('AuthToken')->get('/merchant_share', 'V1\ShareController@merchant');

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

});

Route::fallback(function(){ 
    return response()->json(['error'=>['message' => 'Request Error!']], 404);
});

