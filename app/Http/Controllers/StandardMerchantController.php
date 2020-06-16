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

		// 总发放金额
		$money = 0;

		// 计算交易日期距离开始日期的时间天数
		$diffDay = $this->tradeTime->diffInDays($this->startTime); 

		// 查找出该天数之内的达标的达标设置 包含累积达标和连续达标
		$arrs = array();
		$arrs_lj = array();
		foreach ($this->policy->default_standard_set as $key => $value) {
			// 如果符合当前条件
			if($diffDay > $value['standard_start'] && $diffDay <= $value['standard_end']){
				if($value['standard_type'] == '1') array_push($arrs, $value);
				if($value['standard_type'] == '2') array_push($arrs_lj, $value);
			}
		}

		// 根据当前机器的达标状态  去执行不同的达标政策
		// 在连续达标状态内
		if($this->merchant->standard_statis !="-1")
		{
			foreach ($arrs as $key => $value) {
				// 如果 当前达标条件已经达到 检查当前是否发放本次达标奖励 上一次达标是否达到
				if($this->SumTradeIf($value['standard_start'], $value['standard_end'], $value['standard_trade'] * 100)){
					// 如果当前的达标交易已经发放了
					$haveStandard = \App\MerchantStandard::where('sn', $this->merchant->merchant_sn)->where('policy', $this->policy->id)->where('index', $value['index'])->first();
					// 如果没有找到本次的达标奖励发放情况，  连续达标需要检查上次达标情况有没有达标和发放 
					if(!$haveStandard or empty($haveStandard)){

						$prevStandardArr = array(); 
						foreach ($this->policy->default_standard_set as $k => $v) {
							// 如果符合当前条件
							if($value['standard_start'] > $v['standard_start'] && $value['standard_start'] - 1 == $v['standard_end'] && $v['standard_type'] == '1'){
								$prevStandardArr = $v;
							}
						}

						// 如果上次达标标准不为空 去检查是否达标
						if(!empty($prevStandardArr)){

							$trade = $this->SumTradeIf($prevStandardArr['standard_start'], $prevStandardArr['standard_end'], $prevStandardArr['standard_trade'] * 100);
							
							if(!$trade){
								$this->merchant->standard_statis = -1;
								$this->merchant->save();
								return array('status' => false, 'msg' => '机器上次达标未通过,不发放达标奖励!上次达标条件:机器激活之日起'.$prevStandardArr['standard_start']."-".$prevStandardArr['standard_end']."天内满足".number_format($prevStandardArr['standard_trade'] * 100 , 2, '.', ',')."元交易!");
							}

							// 检查上次达标返现信息
							$haveStandardPrev = \App\MerchantStandard::where('sn', $this->merchant->merchant_sn)->where('policy', $this->policy->id)->where('index', $prevStandardArr['index'])->first();
							if(!$haveStandardPrev or empty($haveStandardPrev)){
								$this->merchant->standard_statis = -1;
								$this->merchant->save();
								return array('status' => false, 'msg' => '机器上次达标发放信息未找到,不发放达标奖励!上次达标条件:机器激活之日起'.$prevStandardArr['standard_start']."-".$prevStandardArr['standard_end']."天内满足".number_format($prevStandardArr['standard_trade'] * 100 , 2, '.', ',')."元交易!");
							}
						}

						// 进行发放达标奖励....
				    	// 根据所属会员的用户组不同 
				    	// 相应的发放模式也不一样
				    	if($this->user->group == 1){

				    		$money = $money + $this->defaultStandard($value);

				    	}

				    	if($this->user->group == 2){
				    		
				    		$money = $money + $this->vipStandard($value);

				    	}

				    	// 写入达标发放信息
				    	$this->addStandardInfo($value);
					}
				}
				# code...
			}
		}



		// 在累积达标状态内
		if($this->merchant->standard_statis_lj !="-1")
		{

			foreach ($arrs_lj as $key => $value) {

				// 如果 当前累积达标条件已经达到 检查上一次累积达标是否达到 与本次达标奖励是否发放
				if($this->SumTradeIf($value['standard_start'], $value['standard_end'], $value['standard_trade'] * 100)){
					// 如果当前的达标交易已经发放了
					$haveStandard = \App\MerchantStandard::where('sn', $this->merchant->merchant_sn)->where('policy', $this->policy->id)->where('index', $value['index'])->first();
					// 发放累积交易达标奖励
					if(!$haveStandard or !empty($haveStandard)){
						// 进行发放
						// 进行发放达标奖励....
				    	// 根据所属会员的用户组不同 
				    	// 相应的发放模式也不一样
				    	if($this->user->group == 1){
				    		$money = $money + $this->defaultStandard($value);
				    	}
				    	
				    	if($this->user->group == 2){
				    		$money = $money + $this->vipStandard($value);
				    	}
					}

					// 写入达标发放信息
					$this->addStandardInfo($value);
				}
			}
		}
		
		return array('status' => true, 'message' => '达标奖励发放完成, 本次发放:'.number_format($money / 100, 2, '.', ',')."元奖励!");
    }


    /**
     * @Author    Pudding
     * @DateTime  2020-06-15
     * @copyright [copyright]
     * @license   [license]
     * @version   [普通用户发放达标奖励]
     * @param     [standard] [< 达标的条件数组 >]
     * @return    [type]      [description]
     */
    public function defaultStandard($standard)
    {
    	// 首先获得用户本人的达标奖励 
    	// 普通用户分润模式为 本人和上级获得达标奖励,
    	$userPrice = $standard['standard_price'] * 100;

    	// 获得上级的达标返现奖励
    	$parentPrice  = $standard['standard_parent_price'] * 100;

    	// 给用户本人发放达标奖励
    	$this->addUserBlance($this->user->id, $userPrice, 9,'机器达标,获得达标奖励:'.number_format($userPrice / 100, 2, '.', ',').'元!');

    	// 如果用户的上级不为0 
    	if($this->user->parent != "0" && $parentPrice > 0){
    		// 给上级发放达标奖励
    		$this->addUserBlance($this->user->parent, $parentPrice, 10,'下级用户机器交易达标,获得达标奖励:'.number_format($parentPrice / 100, 2, '.', ',').'元!');
    	}

    	return $userPrice + $parentPrice;
    }


    /**
     * @Author    Pudding
     * @DateTime  2020-06-15
     * @copyright [copyright]
     * @license   [license]
     * @version   [Vip 用户发放达标奖励]
     * @return    [type]      [description]
     */
    public function vipStandard($standard)
    {
    	// 获得本次达标最高返的奖励金额 
    	$max = $standard['standard_agent_price'] * 100;

    	//
    	$currentMoney = 0;

    	// 获得交易人在本政策下达标的返现金额
    	$userMoney = $this->getStandardMoney($this->user->id, $standard['index']);

    	// 给本人发放奖励
    	if($userMoney <= $max){

			$currentMoney += $userMoney;
			// 发放达标奖励
			$this->addUserBlance($this->user->id, $userMoney, 9,'机器达标,获得达标奖励:'.number_format($userMoney / 100, 2, '.', ',').'元!');
    	}

    	// 如果奖励钱为最高奖励 则没有继续往下发的条件
    	if($currentMoney >= $max) return $currentMoney;
    	
    	// 如果本人的奖励价高于最低结算价
    	// 查找出上级代理中高于最低结算价 并且低于当前结算价的代理
    	// 返回数组格式
    	$parents = $this->getParent($this->user->parent, $currentMoney, $max, $standard['index']);

    	// 如果返回过来有上级合适在范围内的结算信息 进行遍历达标发放
    	if(!empty($parents)){

    		foreach ($parents as $key => $value) {
    			$currentMoney += $value['money'];
    			$this->addUserBlance($value['uid'], $value['money'], 10,'下级用户机器交易达标,获得达标奖励:'.number_format($value['money'] / 100, 2, '.', ',').'结算分润');
    		}
    	}

    	return $currentMoney;
    }



    /**
     * @Author    Pudding
     * @DateTime  2020-05-26
     * @copyright [获取上级]
     * @license   [license]
     * @version   [version]
     * @param     [type]      $pid [description]
     * @return    [type]           [description]
     */
    public function getParent($pid, $currentMoney, $max, $index, $arrs = [])
    {
    	// 如果当前达标奖励大于等于最高结算价 则返回
    	if($currentMoney == $max) return $arrs;
    	// 如果上级为平台 或者没有上级信息了 直接返回
    	if($pid == 0 or !$pid) return $arrs;

    	// 获得上级信息
    	$parent = \App\Buser::where('id', $pid)->first();
    	// 如果找不到上级代理信息 也进行返回
    	if(!$parent or empty($parent)) return $arrs;

    	// 如果上级为普通用户 则继续进行查找
    	if($parent->group == 1) return $this->getParent($parent->parent, $currentMoney, $max, $index, $arrs);

    	// 这时候 上级为代理
    	// 获得上级在该政策下的达标奖励信息
    	$parentPolicyMoney = $this->getStandardMoney($parent->id,  $index);

    	// 如果返回为0。则直接返回数组 意味着上级没有在该政策下参与活动
    	//if($parentPolicyRate == 0) return $arrs;

    	// 如果上级的代理在该政策下的达标奖励与当前相等或小于。则跳过
    	if($parentPolicyMoney <= $currentMoney) return $this->getParent($parent->parent, $currentMoney, $max, $index, $arrs);

    	// 如果代理的达标奖励高于最高奖励 直接返回
    	if($parentPolicyMoney > $max) return $arrs;

    	// 如果 该代理的达标奖励 高于当前  又小于等于最高 
    	if($parentPolicyMoney > $currentMoney && $parentPolicyMoney <= $max){

    		$arrs[]		=	array('uid' => $parent->id, 'money' =>$parentPolicyMoney - $currentMoney);

    		return $this->getParent($parent->parent, $parentPolicyMoney, $max, $index, $arrs);
    	}
    }


    /**
     * @Author    Pudding
     * @DateTime  2020-06-16
     * @copyright [copyright]
     * @license   [license]
     * @version   [获取用户的达标交易返现金额]
     * @return    [type]      [description]
     */
    public function getStandardMoney($user, $index)
    {
    	$arrs = array();
    	// 查询该政策下 会员在当前达标条件下的返现金额
    	$userPolicy = \App\UserPolicy::where('user_id', $user)->where('policy_id', $this->policy->id)->first();

    	// 如果当前会员有该政策信息
    	if($userPolicy){
    		foreach ($userPolicy->standard as $key => $value){
    			if($value['index'] == $index) $arrs = $value;
    		}
    	}else{
    		foreach ($this->policy->default_standard_set as $key => $value){
    			if($value['index'] == $index) $arrs = $value;
    		}
    	}
    	return  $arrs['standard_agent_price'] * 100;
    } 


    /**
     * @Author    Pudding
     * @DateTime  2020-05-27
     * @copyright [copyright]
     * @license   [license]
     * @version   [增加用户余额 分润余额 分润记录]
     * @param     [type]      $uid   [description]
     * @param     [type]      $money [description]
     */
    protected function addUserBlance($uid, $money, $type = 1, $remark='分润发放成功!')
    {
    	// 写入分润记录
    	\App\Cash::create(['order'=> $this->trade->order, 'user_id' => $uid, 'cash_money'=>$money, 'cash_type'=> $type,'remark' => $remark ]);
    	// 写入达标发放信息

    	// 写入用户钱包余额
    	\App\BuserWallet::where('user_id', $uid)->increment('return_blance', $money);
    }


    /**
     * @Author    Pudding
     * @DateTime  2020-06-15
     * @copyright [copyright]
     * @license   [license]
     * @version   [写入达标发放信息情况]
     */
    public function addStandardInfo($standard)
    {	
    	$standard['standard_trade'] = $standard['standard_trade'] * 100;

    	$standard['standard_agent_price'] = $standard['standard_agent_price'] * 100;

    	$standard['standard_price'] = $standard['standard_price'] * 100;

    	$standard['standard_parent_price'] = $standard['standard_parent_price'] * 100;

    	return \App\MerchantStandard::create([
    		'sn'		=>	$this->merchant->merchant_sn,
    		'policy'	=>	$this->policy->id,
    		'index'		=>	$standard['index'],
    		'remark'	=>	json_encode($standard),
    	]);
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

    	$this->startTime= Carbon::parse($this->merchant->active_time ?? $this->merchant->bind_time);

    	$startTime = $this->startTime->addDays($start)->toDateTimeString();
    	
    	$endTime   = $this->startTime->addDays($end)->toDateTimeString();
    	
    	// 查询出这时间段内的交易 该终端的
    	$trade = \App\Trade::where('merchant_sn', $this->merchant->merchant_sn)->whereBetween('trade_time', [$startTime, $endTime])->where('trade_status', '1')->distinct('order')->sum('money');

    	return $trade >=  $count ? true : false;
    }
}
