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