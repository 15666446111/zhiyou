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

Route::get('/',  'HomeController@index');

Auth::routes();


/**
 * @version [<汇付方交易推送地址>] [<description>]
 * @return  [交易数据推送处理]   [<description>]
 * @version [<交易数据处理] [<description>]
*/
Route::post('/trade', 'V1\TradeNotifyController@trade'); 



/**
 * @version [<团队邀请人注册 扫描二维码>] [<description>]
 * @author  [Pudding] <[755969423@qq.com]>
 * @version [<会员注册>] [<description>]
 */
Route::get('/team/{code}', 'RegisterController@team');

/**
 * @version [<团队邀请人注册 扫描二维码>] [<description>]
 * @author  [Pudding] <[755969423@qq.com]>
 * @version [<会员注册>] [<description>]
 */
Route::post('/team/{code}', 'RegisterController@team_in')->name('register');


/**
 * @version [<团队邀请人注册 扩展普通用户 扫描二维码>] [<description>]
 * @author  [Pudding] <[755969423@qq.com]>
 * @version [<会员注册>] [<description>]
 */
Route::get('/extendUser/{code}', 'RegisterController@extendUser');

/**
 * @version [<团队邀请人注册 扩展普通用户 扫描二维码>] [<description>]
 * @author  [Pudding] <[755969423@qq.com]>
 * @version [<会员注册>] [<description>]
 */
Route::post('/extendUser/{code}', 'RegisterController@extendUserIn');


/**
 * @version [<扫码 提交表单 申请机器 无需注册即可申请>] [<description>]
 * @author  [Pudding] <[755969423@qq.com]>
 * @version [<朋友圈申请机器>] [<description>]
 */
Route::get('/extendTemail/{code}', 'RegisterController@extendTemail');

/**
 * @version [<扫码 提交表单 申请机器 无需注册即可申请>] [<description>]
 * @author  [Pudding] <[755969423@qq.com]>
 * @version [<提交表单>] [<description>]
 */
Route::post('/extendTemail/{code}', 'RegisterController@extendTemailIn');