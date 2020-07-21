<?php

namespace App\Http\Controllers\V1;

use Carbon\Carbon;
use Illuminate\Http\Request;

class StatisticController
{	
    /**
     * [$Users 当前登录的用户信息]
     * @var [Collection]
     */
    protected  $Users;

    /**
     * [$Users 保存当前登录用户的所有团队会员ID]
     * @var [Array]
     */
    protected  $Teams;

    /**
     * [$Users 查询的时间截止]
     * @var [Time String]
     */
    protected  $EndTime; 

    /**
     * [$Users 查询的开始时间]
     * @var [Time String]
     */
    protected  $StartTime;


    /**
     * [$team 我的团队用户 ]
     * @var [array]
     */
    protected  $team;


    /**
     * [__construct 初始化  赋值变量]
     * @author Pudding
     * @DateTime 2020-04-10T16:27:28+0800
     * @param    [type]                   $user [description]
     * @param    [type]                   $time [description]
     * @param    string                   $end  [description]
     */
    public function __construct($user, $time, $end = '')
    {
        // 初始化的时候 将当前登录的用户信息给到Users
        $this->Users = $user;

        // 根据time的类型 获得开始时间  可以直接赋值
        switch ($time) {
            case 'month':
                $this->StartTime = Carbon::now()->startOfMonth()->toDateTimeString();
                break;
            case 'day':
                $this->StartTime = Carbon::today()->toDateTimeString();
                break;
            case 'all':
                $this->StartTime = Carbon::createFromFormat('Y-m-d H', '1970-01-01 00')->toDateTimeString();
                break;
            default:
                $this->StartTime = $time;
                break;
        }
        
        $this->EndTime = Carbon::now()->toDateTimeString();


        $this->team = $this->getMyTeam();

        $this->team[] = $user->id;
    }

    /**
     * [getMyTeam 获取我的团队所有会员ID]
     * @author Pudding
     * @DateTime 2020-04-10T16:21:30+0800
     * @return   [type]                   [description]
     */
    public function getMyTeam()
    {
        return \App\BuserParent::where('parents', 'like', "%\_".$this->Users->id."\_%")->pluck('user_id')->toArray();
    }

    /**
     * [getTeam 获取新增的商户数量]
     * @author Pudding
     * @DateTime 2020-04-10T16:05:18+0800
     * @return   [type]                   [description]
     */
    public function getNewAddMerchant()
    {
        return \App\Merchant::where('bind_status', '1')->whereBetween('bind_time', [ $this->StartTime,  $this->EndTime])
                ->whereIn('user_id', $this->team)->count();     
    }

    /**
     * [getTradeSum 获取新的交易金额]
     * @author Pudding
     * @DateTime 2020-04-10T16:31:07+0800
     * @param    [type]                   $rule [查询自己的还是团队的]
     * @return   [type]                         [description]
     */
    public function getTradeSum($rule = 'team')
    {
        $Arr = $rule == 'team' ? $this->team :  array($this->Users->id);

        return \App\Trade::where('trade_status', '>=', '1')->whereBetween('trade_time', [ $this->StartTime,  $this->EndTime])
                ->whereHas('merchants_sn', function($q) use ($Arr){
                    $q->whereIn('user_id', $Arr);
                })->sum('money');
    }

    /**
     * [getTeam 获取新增的伙伴数量]
     * @author Pudding
     * @DateTime 2020-04-10T16:05:18+0800
     * @return   [type]                   [description]
     */
    public function getNewAddTeamCount()
    {
        return \App\Buser::whereIn('id', $this->team)->whereBetween('created_at', [ $this->StartTime, $this->EndTime])->count();
    }

    /**
     * @Author    Pudding
     * @DateTime  2020-06-06
     * @copyright [copyright]
     * @license   [获取新增激活商户]
     * @version   [version]
     * @return    [type]      [description]
     */
    public function getNewActiveMerchant()
    {
        return \App\Merchant::where('active_status', '1')->whereBetween('active_time', [ $this->StartTime,  $this->EndTime])
                ->whereIn('user_id', $this->team)->count();   
    }

    /**
     * @Author    Pudding
     * @DateTime  2020-06-05
     * @copyright [copyright]
     * @license   [license]
     * @version   [获取团队月收益]
     * @return    [type]      [description]
     */
    public function getIncome()
    {
        return \App\Cash::whereIn('user_id', $this->team)->whereBetween('created_at', [ $this->StartTime, $this->EndTime])->sum('cash_money');
    }

}
