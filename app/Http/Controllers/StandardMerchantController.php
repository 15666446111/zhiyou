<?php

namespace App\Http\Controllers;

use App\Trade;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StandardMerchantController extends Controller
{
	/**
	 * [$trade 当前交易对象]
	 * @var [type]
	 */
	protected $trade;

	/**
	 * [$user 当前交易机器持有人]
	 * @var [type]
	 */
	protected $user;

	/**
	 * [$policy 当前机器所在的政策]
	 * @var [type]
	 */
	protected $policy;

	/**
	 * [$merchant 当前机具]
	 * @var [type]
	 */
	protected $merchant;

	/**
	 * [$tradeTime 交易时间]
	 * @var [type]
	 */
	protected $tradeTime;

	/**
	 * [$activeTime 注册开通时间]
	 * @var [type]
	 */
	protected $startTime;
	/**
	 * @Author    Pudding
	 * @DateTime  2020-06-11
	 * @copyright [copyright]
	 * @license   [license]
	 * @version   [version]
	 * @param     Trade       $trade [description]
	 */
	public function __construct(Trade $trade)
	{
		$this->trade 	= $trade;

		$this->merchant = $trade->merchants_sn;

		$this->policy 	= $trade->merchants_sn->policys;

		$this->user  	= $trade->merchants_sn->busers;
	}

    /**
     * @version [<vector>] [< 达标奖励设置 >]
     */
    public function standard()
    {
    	if(!$this->trade->trade_time){
    		return array('status' => false, 'message' => '没有交易时间,无法计算达标');
    	}

		$this->tradeTime= Carbon::parse($this->trade->trade_time);

		// 如果没有绑定时间和激活时间
		if(!$this->merchant->active_time && !$this->merchant->bind_time){
			return array('status' => false, 'message' => '找不到机器的激活时间/绑定时间,无法计算达标');
		}
		$this->startTime= Carbon::parse($this->merchant->active_time ?? $this->merchant->bind_time);

		// 如果交易时间小于起始时间 ， 不进行达标计算
		if($this->startTime->gt($this->tradeTime)){
			return array('status' => false, 'message' => '激活/绑定时间大于交易时间,不进行达标计算');
		}

		// 计算交易日期距离开始日期的时间天数
		$diffDay = $this->tradeTime->diffInDays($this->startTime); 

		// 查找出该天数之内的达标的达标设置
		foreach ($this->policy->default_standard_set as $key => $value) {
			// 如果符合当前条件
			if($diffDay > $value['standard_start'] && $diffDay <= $value['standard_end'] && $value['standard_type'] == 1){
				dd($this->SumTradeIf($value['standard_start'], $value['standard_end'], $value['standard_trade'] * 100));
			}
		}
    }


    /**
     * @Author    Pudding
     * @DateTime  2020-06-11
     * @copyright [copyright]
     * @license   [license]
     * @version   [查询时间段内的交易是否满足达标要求]
     * @param     [type]      $start [description]
     * @param     [type]      $end   [description]
     * @param     [type]      $count [description]
     */
    public function SumTradeIf($start, $end, $count)
    {

    	$startTime = $this->startTime->addDays($start)->toDateTimeString();
    	
    	$endTime   = $this->startTime->addDays($end)->toDateTimeString();

    	// 查询出这时间段内的交易 该终端的
    	$trade = \App\Trade::where('merchant_sn', $this->merchant->merchant_sn)->whereBetween('trade_time', [$startTime, $endTime])->where('trade_status', '1')->distinct('order')->sum('money');

    	return $trade >=  $count ? true : false;
    }
}
