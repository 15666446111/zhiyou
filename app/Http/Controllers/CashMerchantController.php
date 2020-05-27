<?php

namespace App\Http\Controllers;

use App\Trade;
use Illuminate\Http\Request;

class CashMerchantController extends Controller
{
	/**
	 * [$trade_fee  交易费率 (万分位)]
	 * @var [int]
	 */
    protected $trade_fee;

    /**
     * [$min_fee 最低结算费率 (万分位) = 政策活动所设置的最低结算价]
     * @var [int]
     */
    protected $min_fee;


    /**
     * [$policy 该笔交易所属的政策活动]
     * @var [orm model]
     */
    protected $policy;

    /**
     * [$user 该笔交易所属的用户]
     * @var [orm user model]
     */
    protected $user;


    /**
     * @Author    Pudding
     * @DateTime  2020-05-27
     * @copyright [初始化方法]
     * @license   [license]
     * @version   [version]
     * @param     Trade       $trade [ 参数为trade交易数据模型]
     */
	public function __construct(Trade $trade)
	{
      	// 获取交易费率 (万分位)
      	// 如果交易数据中有费率存在 并且为数字类型 且大于0 则使用该交易费率
      	// 如果交易数据中费率不存在或者小雨0 则使用 手续费 / 交易金额 方式计算手续费。单位 万分位 
      	if($trade->rate && is_numeric($trade->rate) && $trade->rate > 0){
      		$this->trade_fee = $trade->rate;
      	}else{
      		// 计算方式的基本准则为 交易金额必须为正整数 
      		// 如果不满足条件 则废了为0
      		if($trade->money && is_numeric($trade->money) && $trade->money > 0){
      			// 计算手续费的优先顺序 1、手续费 / 交易金额。 2、 （交易金额 - 结算金额）/ 交易金额 
      			if($trade->rate_money && is_numeric($trade->rate_money) && $trade->rate_money > 0){
      				$this->trade_fee = $trade->rate_money / $trade->money;
      			// 计算手续费 其他方式
      			}elseif($trade->real_money && is_numeric($trade->real_money) && $trade->real_money > 0){
      				$this->trade_fee = ($trade->money - $trade->real_money) / $trade->money;
      			}else
      				$this->trade_fee = 0 ;
      		}else
      			$this->trade_fee = 0 ;
      	}


      	//  获得结算费率 最低发放到的结算价
      	//  因 交易持有人的级别不同 分润模式不同
      	//  因 交易类型不同 交易卡类型不同 结算低价不同
      	//  故而需要根据政策活动进行设置
      	$this->policy = $trade->merchants->policys;

      	// 循环设置的结算价
      	foreach ($this->policy->sett_price as $key => $value) {
      		if($value['trade_type'] == $trade->trade_type && $value['trade_bank'] == $trade->card_type){
      			$this->min_fee = $value['setprice'];
      		}else
      			$this->min_fee = 0;
      	}
      	// 
      	// $this->min_fee = $this->policy->sett_price;



      	$this->user   = $trade->merchants->busers;





   	}

	/**
	 * @Author    Pudding
	 * @DateTime  2020-05-26
	 * @copyright [交易数据分润类]
	 * @license   [license]
	 * @version   [version]
	 * @return    [type]      [description]
	 */
    public function cash(Trade $trades)
    {
    	// 1. 获取到该机器的所属政策活动
    	// 2. 判断该笔交易类型是否在该政策活动下允许分润
    	// 3. 获得该机器所属的会员 以及会员组sett_price
    	// 4. 根据会员组不同 分配不同的分润模式
    	// 
    	return $this->min_fee;

    	return $this->user;
    	
    	return $this->policy;

    	return $this->trade_fee;
    }


    /**
     * @Author    Pudding
     * @DateTime  2020-05-26
     * @copyright [获取上级]
     * @license   [license]
     * @version   [version]
     * @param     [type]      $uid [description]
     * @return    [type]           [description]
     */
    public  function getParent($uid)
    {

    }

}
