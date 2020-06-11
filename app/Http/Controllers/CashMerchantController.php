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
     * [$trade 该笔交易订单信息]
     * @var [orm trade model]
     */
    protected $trade;

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

		$this->trade = $trade;

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
      				$this->trade_fee = number_format($trade->rate_money / $trade->money * 10000);
      			// 计算手续费 其他方式
      			}elseif($trade->real_money && is_numeric($trade->real_money) && $trade->real_money > 0){
      				$this->trade_fee = number_format(($trade->money - $trade->real_money) / $trade->money);
      			}else
      				$this->trade_fee = 0 ;
      		}else
      			$this->trade_fee = 0 ;
      	}

      	//  获得结算费率 最低发放到的结算价
      	//  因 交易持有人的级别不同 分润模式不同
      	//  因 交易类型不同 交易卡类型不同 结算低价不同
      	//  故而需要根据政策活动进行设置
      	$this->policy 	= $trade->merchants_sn->policys;


      	$this->min_fee 	= 0;
      	// 循环设置的结算价
      	foreach ($this->policy->sett_price as $key => $value) {
      		if($value['trade_type'] == $trade->trade_type && $value['trade_bank'] == $trade->card_type &&  $value['open']){
      			$this->min_fee = $value['setprice'];
      		}		
      	}
      	
      	//$this->min_fee = $this->policy->sett_price;
      	// $this->min_fee = $this->policy->sett_price;


      	// 设置user
      	$this->user   = $trade->merchants_sn->busers;
   	}

	/**
	 * @Author    Pudding
	 * @DateTime  2020-05-26
	 * @copyright [交易数据分润类]
	 * @license   [license]
	 * @version   [version]
	 * @return    [type]      [description]
	 */
    public function cash() : array
    {
    	// 1. 获取到该机器的所属政策活动
    	// 2. 判断该笔交易类型是否在该政策活动下允许分润
    	// 3. 获得该机器所属的会员 以及会员组sett_price
    	// 4. 根据会员组不同 分配不同的分润模式

    	if($this->trade_fee <= 0){
    		return array('status' => false, 'message' => '交易费率无法计算/计算失败,分润失败');
    	}

    	if($this->min_fee <= 0){
    		return array('status' => false, 'message' => '最低结算价无法获取/获取失败/交易类型不在范围内,分润失败');
    	}

    	if($this->min_fee >= $this->trade_fee){
    		return array('status' => false, 'message' => '最低结算价高于交易费率,分润失败');
    	}

    	if(!$this->policy or empty($this->policy)){
    		return array('status' => false, 'message' => '找不到机器隶属活动政策,分润失败');
    	}
    	
    	if(!$this->user or empty($this->user)){
    		return array('status' => false, 'message' => '找不到机器隶属会员,分润失败');
    	}

    	// 根据所属会员的用户组不同 
    	// 相应的分润模式也不一样
    	if($this->user->group == 1){

    		return $this->defaultCash();

    	}elseif($this->user->group == 2){

    		return $this->vipCash();

    	}else
    		return array('status' => false, 'message' => '机器持有人不在分润用户组,分润失败');
    }



    /**
     * @Author    Pudding
     * @DateTime  2020-05-27
     * @copyright [copyright]
     * @license   [license]
     * @version   [机器持有人为普通用户的时候 分润模式为 普通用户获得直接万2 间推获得万1。， 其余按照结算价差价分润]
     * @return    [type]      [description]
     */
    public function defaultCash()
    {
    	// 首先获得用户的直推交易分润
    	$userRate = $this->policy->default_push;
    	// 计算直推的交易推荐分润
    	$rateMoney = $this->trade->money * $userRate / 10000;
    	// 如果该活动设置了普通用户推荐交易分润奖励 则进行直推分润奖励
    	if($userRate > 0){
	    	// 写入用户分润表 用户钱包表
	    	$this->addUserBlance($this->user->id, $rateMoney, 3,'机器持有人获得直推分润:'.number_format($this->trade->money / 100, 2, '.', ',').'*('.($userRate / 10000).')结算分润');
    	}

    	// 获得该政策下间推的推荐配置  如果需要分润
    	if($this->policy->indirect_push > 0){
    		// 获得该会员的临近上级(必须是代理)
    		$indirectMoney   = $this->trade->money * $this->policy->indirect_push / 10000;

    		// 计算一共分出去多少钱了
    		$rateMoney += $indirectMoney;

    		// 计算一共的推荐费率分多少出去
    		$userRate  += $this->policy->indirect_push;

    		// 如果用户还有上级的话 
    		if($this->user->parent != 0 ){
    			$this->addUserBlance($this->user->parent, $indirectMoney, 4,'下级普通用户持有机器交易,获得间推分润:'.number_format($this->trade->money / 100, 2, '.', ',').'*('.($this->policy->indirect_push / 10000).')结算分润');
    		}
    	}


        // 获得该用户在该政策下的最低结算价。该交易类型下的卡交易类型。
        $currentRate = $this->getRate($this->user->id, $this->policy->id);
        // 如果分出去之后 本人还有结算差价 给本人分结算差价
        if($this->trade_fee - $userRate > $currentRate){
            $currentRateBe = $this->trade_fee - $userRate - $currentRate;
            $userRate     += $currentRateBe;
            $currentRateMoney = $this->trade->money * $currentRateBe / 10000;
            $rateMoney += $currentRateMoney;
            $this->addUserBlance($this->user->id, $currentRateMoney, 1,'机器持有人获得结算差价:'.number_format($this->trade->money / 100, 2, '.', ',').'*('.($currentRateBe / 10000).')结算分润');
        }


    	if($this->user->parent != 0 ){
	    	//  交易推荐奖励分完之后  如果该用户有上级 去查找第一个临近的代理
    		// 总计分出去的差价
    		$currate = $this->trade_fee - $userRate;

    		$parentRate = $this->getParent($this->user->parent, $currate);

	    	// 如果返回过来有上级合适在范围内的结算信息 进行遍历分润发放
	    	if(!empty($parentRate)){

	    		foreach ($parentRate as $key => $value) {
	    
	    			$cashMoney = $this->trade->money * $value['urate'] / 10000;

	    			$rateMoney += $cashMoney;

	    			$this->addUserBlance($value['uid'], $cashMoney, 2,'下级交易,获得结算差价:'.number_format($this->trade->money / 100, 2, '.', ',').'*('.($value['urate'] / 10000).')结算分润');

	    		}

	    	}
	    }

    	return array('status' => true, 'message' => '订单分润完成,共分润:'.($rateMoney / 100).'元!');    	
    }


    /**
     * @Author    Pudding
     * @DateTime  2020-05-27
     * @copyright [pudding]
     * @license   [license]
     * @version   [机器持有人为代理的时候。分润模式为 代理获得结算差价, 上级获得差价， 直到最低结算价或最顶级 ]
     * @return    [type]      [description]
     */
    protected function vipCash()
    {
    	// 首先获得该代理在该政策下的最低结算金额。该交易类型下的卡交易类型。
    	$userRate = $this->getRate($this->user->id, $this->policy->id);

    	if($userRate <= 0){
    		return array('status' => false, 'message' => '未找到持有人所属政策配置,分润失败');
    	}

    	if($userRate < $this->min_fee){
    		return array('status' => false, 'message' => '持有人结算价低于/等于最低结算价,不进行分润');
    	}

    	if($userRate > $this->trade_fee){
    		return array('status' => false, 'message' => '持有人结算价高于交易费率,不进行分润');
    	}
    	// 计算费率差
    	$rate 		= $this->trade_fee - $userRate;

    	// 计算结算差价
    	$rateMoney 	= $this->trade->money * $rate / 10000;

    	// 写入用户分润表 用户钱包表
    	$this->addUserBlance($this->user->id, $rateMoney, 1,'机器持有人获得结算差价:'.number_format($this->trade->money / 100, 2, '.', ',').'*('.($rate / 10000).')结算分润');

    	// 如果本人的结算价高于最低结算价
    	// 查找出上级代理中高于最低结算价 并且低于当前结算价的代理
    	// 返回数组格式
    	$parents = $userRate > $this->min_fee ? $this->getParent($this->user->parent, $userRate) : [];

    	// 如果返回过来有上级合适在范围内的结算信息 进行遍历分润发放
    	if(!empty($parents)){

    		foreach ($parents as $key => $value) {
    
    			$cashMoney = $this->trade->money * $value['urate'] / 10000;

    			$rateMoney += $cashMoney;

    			$this->addUserBlance($value['uid'], $cashMoney, 2,'下级代理交易,获得结算差价:'.number_format($this->trade->money / 100, 2, '.', ',').'*('.($value['urate'] / 10000).')结算分润');

    		}

    	}

    	// 给机器持有人进行结算
    	return array('status' => true, 'message' => '订单分润完成,共分润:'.($rateMoney / 100).'元!');
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
    public function getParent($pid, $currate, $arrs = [])
    {
    	// 如果当前费率已是最小费率。则返回
    	if($currate == $this->min_fee) return $arrs;
    	// 如果上级为平台 或者没有上级信息了 直接返回
    	if($pid == 0 or !$pid) return $arrs;
    	// 获得上级信息
    	$parent = \App\Buser::where('id', $pid)->first();
    	// 如果找不到上级代理信息 也进行返回
    	if(!$parent or empty($parent)) return $arrs;
    	// 如果上级为普通用户 则继续进行查找
    	if($parent->group == 1) return $this->getParent($parent->parent, $currate, $arrs); 
    	// 这时候 上级为代理
    	// 获得上级在该政策下的结算信息
    	$parentPolicyRate = $this->getRate($parent->id, $this->policy->id);

    	// 如果返回为0。则直接返回数组 意味着上级没有在该政策下参与活动
    	if($parentPolicyRate == 0) return $arrs;

    	// 如果上级的代理与该会员的结算价相同则跳过
    	if($parentPolicyRate >= $currate) return $this->getParent($parent->parent, $currate, $arrs);

    	// 如果代理的该政策结算价小于最低结算价 直接返回
    	if($parentPolicyRate < $this->min_fee) return $arrs;

    	// 如果 该代理的结算价小于下级 又高于最小结算价 进行组合
    	if($parentPolicyRate < $currate && $parentPolicyRate >= $this->min_fee){
    		$arrs[]		=	array('uid' => $parent->id, 'urate' =>$currate - $parentPolicyRate);
    		return $this->getParent($parent->parent, $parentPolicyRate, $arrs);
    	}
    }

    /**
     * @Author    Pudding
     * @DateTime  2020-05-27
     * @copyright [copyright]
     * @license   [license]
     * @version   [根据userid policyid 获取该用户下 该政策的 该交易类型下 卡类型的交易结算价]
     * @param     [type]      $uid [description]
     * @param     [type]      $pid [description]
     * @return    [type]           [description]
     */
    protected function getRate($uid, $pid)
    {
    	$userRate = 0;

    	// 获取用户在该政策的结算价配置
    	$userPolicy = \App\UserPolicy::where('user_id', $uid)->where('policy_id', $pid)->first();

    	if(!$userPolicy or empty($userPolicy)){
    		foreach ($this->policy->sett_price as $key => $value) {
				if($value['trade_type'] == $this->trade->trade_type && $value['trade_bank'] == $this->trade->card_type && $value['open']){
      				$userRate = $value['defaultPrice'];
      			}	
    		}

    		return $userRate;
    	}	
    	
    	/**
    	 * [$key description]
    	 * @var [type]
    	 */
      	foreach ($userPolicy->sett_price as $key => $value) {
      		if($value['trade_type'] == $this->trade->trade_type && $value['trade_bank'] == $this->trade->card_type &&  $value['open']){
      			$userRate = $value['setprice'];
      		}		
      	}

    	return $userRate;
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
    	// 写入用户钱包余额
    	\App\BuserWallet::where('user_id', $uid)->increment('cash_blance', $money);
    }

}
