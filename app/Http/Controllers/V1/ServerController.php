<?php

namespace App\Http\Controllers\V1;

use DB;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ServerController extends Controller
{
	/**
	 * [$dateType 日期类型]
	 * @var [ month or day]
	 */
	protected $dateType;

	/**
	 * [$date 查询日期]
	 * @var [type]
	 */
	protected $date;

	/**
	 * [$Type 查询的类型]
	 * @var [本人 或者 团队]
	 */
	protected $Type;


	/**
	 * [$user 当前登陆会员]
	 * @var [type]
	 */
	protected $user;


	/**
	 * [$users 查询的用户]
	 * @var [type]
	 */
	protected $users;
	/**
	 * @Author    Pudding
	 * @DateTime  2020-06-05
	 * @copyright [copyright]
	 * @license   [license]
	 * @version   [初始化数据 查询条件]
	 * @param     [type]      $dateType [description]
	 * @param     [type]      $date     [description]
	 * @param     [type]      $current  [description]
	 */
    public function __construct($dateType, $date, $current, $user)
    {

    	$this->dateType = $dateType == "month" ? $dateType : "day";

    	/**
    	 * [$this->dateType description]
    	 * @var [type]
    	 */
    	$this->date     = $date;


    	$this->Type     = $current;

    	//
    	$this->user     = $user;
    	//dd($this->user);
    	if($this->Type == "current") 
    		$this->users = array($this->user->id);

    	if($this->Type == "team"){
    		$this->users   = \App\BuserParent::where('parents', 'like', '%_'.$this->user->id.'_%')->pluck('user_id')->toArray();
    		$this->users[] = $this->user->id;
    	}
    }


    /**
     * @Author    Pudding
     * @DateTime  2020-06-05
     * @copyright [copyright]
     * @license   [license]
     * @version   [获取交易详情的所有信息]
     * @return    [type]      [description]
     */
    public function getInfo()
    {
    	$arrs = array();

    	// 返回查询的日期
    	$arrs['date'] = $this->getDate();

    	$arrs['trade']		= $this->getTrade();

    	$arrs['activeCount'] = $this->getActiveCount();

    	$arrs['income']		= $this->getIncome();

    	return $arrs;
    }



    /**
     * @Author    Pudding
     * @DateTime  2020-06-05
     * @copyright [copyright]
     * @license   [license]
     * @version   [version]
     * @return    [type]      [description]
     */
    public function getTrade()
    {
    	//DB::connection()->enableQueryLog();#开启执行日志

		$dt = Carbon::parse($this->date);

		$users = $this->users;

    	$select = \App\Trade::whereHas('merchants', function($q) use ($users){
    		$q->whereIn('user_id', $users);
    	});

    	if($this->dateType == "month"){
    		$select->whereMonth('created_at', $dt->month)->whereYear('created_at', $dt->year);
    	}

    	if($this->dateType == "day"){
    		$select->whereDate('created_at', $this->date);
    	}

    	return $select->sum('money');
    }


    /**
     * @Author    Pudding
     * @DateTime  2020-06-05
     * @copyright [copyright]
     * @license   [license]
     * @version   [ 获取激活总数]
     * @return    [type]      [description]
     */
    public function getActiveCount()
    {
    	//DB::connection()->enableQueryLog();#开启执行日志

		$dt = Carbon::parse($this->date);

    	$select = \App\Merchant::whereIn('user_id', $this->users);

    	if($this->dateType == "month"){
    		$select->whereMonth('active_time', $dt->month)->whereYear('active_time', $dt->year);
    	}

    	if($this->dateType == "day"){
    		$select->whereDate('active_time', $this->date);
    	}

    	return $select->count();
    }

    /**
     * @Author    Pudding
     * @DateTime  2020-06-05
     * @copyright [copyright]
     * @license   [license]
     * @version   [获取收益总额]
     * @return    [type]      [description]
     */
    public function getIncome()
    {
    	//DB::connection()->enableQueryLog();#开启执行日志

		$dt = Carbon::parse($this->date);

    	$select = \App\Cash::whereIn('user_id', $this->users)->where('status', '1');

    	if($this->dateType == "month"){
    		$select->whereMonth('created_at', $dt->month)->whereYear('created_at', $dt->year);
    	}

    	if($this->dateType == "day"){
    		$select->whereDate('created_at', $this->date);
    	}

    	return $select->sum('cash_money');
    }

    /**
     * @Author    Pudding
     * @DateTime  2020-06-05
     * @copyright [copyright]
     * @license   [license]
     * @version   [返回日期]
     * @return    [type]      [description]
     */
    protected function getDate()
    {
    	return array('date'=>$this->date, 'type' => $this->dateType);
    }

}
