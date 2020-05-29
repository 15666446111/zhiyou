<?php

namespace App\Http\Controllers;

use App\Trade;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ActiveMerchantController extends Controller
{

	/**
	 * [$active_money  要分下去的激活返现。根据用户组配置不同则不同]
	 * @var [int]
	 */
    protected $active_money;


	/**
	 * [$active_money_max  激活返现最高分多少。根据用户组配置不同则不同]
	 * @var [int]
	 */
    protected $active_money_max;


    /**
     * [$trade 该笔交易订单信息]
     * @var [orm trade model]
     */
    protected $trade;


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

		$this->trade 			= $trade;

		$this->policy 			= $trade->merchants->policys;
      	
      	$this->user   			= $trade->merchants->busers;

      	$this->active_money_max = 0;
		// 根据当前用户的用户组获得不同的返现配置
		if($this->user->group == "1"){
			$this->active_money_max = $this->policy->default_active_set['return_money'];
		}

		if($this->user->group == "2"){
			$this->active_money_max = $this->policy->vip_active_set['return_money'];
		}
    }

    /**
     * @Author    Pudding
     * @DateTime  2020-05-29
     * @copyright [copyright]
     * @license   [license]
     * @version   [用户激活进行返现]
     * @return    [type]      [description]
     */
    public  function active() : array
    {
    	if($this->trade->merchants->active_status or $this->trade->merchants->active_status != ""){
    		return array('status' => false, 'message' => '该机器已经激活,不再发放激活返现');
    	}

    	$pushMoney = 0;

        $this->trade->merchants->active_status = 1;

        $this->trade->merchants->active_time = Carbon::now()->toDateTimeString();

        $this->trade->merchants->save();


    	if($this->user->parent > 0 ){
    		// 获得直接上级信息
    		$parent = \App\Buser::where('id', $this->user->parent)->first();

	    	// 首先获得该政策的直推激活奖励
	    	$pushParent = $this->policy->default_active;

	    	$pushMoney += $pushParent;
    		
    		if($pushParent > 0 && $parent ){
    			$this->addUserBlance($parent->id, $pushParent, 6,'直推激活:获得'.number_format($pushParent / 100, 2, '.', ',').')激活推荐奖励');
    		}

    		// 如果还有上级 并且设置了推荐奖励。
    		if($parent->parent > 0 && $this->policy->indirect_active > 0 ){

    			$pushMoney += $this->policy->indirect_active;

    			$this->addUserBlance($parent->parent, $this->policy->indirect_active, 7,'间推激活:获得'.number_format($this->policy->indirect_active / 100, 2, '.', ',').')激活推荐奖励');
    		}

    	}


    	// 根据用户获取该用户应获得的激活返现
    	$pushUser = $this->getActiveMoney( $this->user->id );

    	if($pushUser != 0){
    		$pushMoney += $pushUser;
    		$this->addUserBlance($this->user->id, $pushUser, 5,'机器激活:获得'.number_format($pushUser / 100, 2, '.', ',').')激活返现奖励');
    	}

		// 如果激活奖励金额不为最大的激活奖励金额
		// 计算上级代理中的奖励差价
		if($this->active_money_max - $pushUser > 0){
			// 获取上级代理中有没有差价
			$parents = $this->getParent($this->user->parent, $pushUser);

			// 如果上级有差价存在
			if($parents && !empty($parents)){

				foreach ($parents as $key => $value) {
					$pushMoney += $value['money'];
					$this->addUserBlance($value['uid'], $value['money'], 8,'团队激活:获得'.number_format($value['money'] / 100, 2, '.', ',').')激活推荐奖励');
				}

			}	
		}


    	return array('status' => true, 'message' => '机器激活完成,共激活返现:'.($pushMoney / 100).'元!');
    }


    /**
     * @Author    Pudding
     * @DateTime  2020-05-29
     * @copyright [copyright]
     * @license   [license]
     * @version   [获取某用户在当前政策下 激活返现多少金额]
     * @param     [type]      $uid [description]
     * @return    [type]           [description]
     */
    public function getActiveMoney($uid)
    {
    	// 获取用户在该政策的结算价配置
    	$userPolicy = \App\UserPolicy::where('user_id', $uid)->where('policy_id', $this->policy->id)->first();

    	// 获取用户信息
    	$user       = \App\Buser::where('id', $uid)->first();

    	// 找不到用户的情况下
    	if(!$user or empty( $user )) return 0;
    	
    	if($user->group == "1"){
    		return $userPolicy ? $userPolicy->default_active_set['return_money'] : $this->policy->default_active_set['return_money'];
    	}

    	if($user->group == "2"){
    		return $userPolicy ? $userPolicy->vip_active_set['return_money'] : $this->policy->vip_active_set['return_money'];
    	}
    	// 如果找不到该用户在该政策下的活动 读取默认配置
    }

    /**
     * @Author    Pudding
     * @DateTime  2020-05-29
     * @copyright [copyright]
     * @license   [license]
     * @version   [获取uid的第一个临近的代理上级]
     * @param     [type]      $uid [description]
     * @return    [type]           [description]
     */
    public function getParent($uid, $money, $arrs=[])
    {
    	if($uid == 0) return $arrs;
    	// 
    	$parent = \App\Buser::where('id', $uid)->first();

    	// 如果查找失败或者找不到
    	if(!$parent or empty($parent)) return $arrs;

    	// 如果是代理 获得该代理的推荐激活返现
    	if($parent->group == "2"){
    		// 获得当前代理在该活动政策下的激活返现
    		$currentMoney = $this->getActiveMoney($parent->id);

    		// 如果激活返现金额高于最高的激活返现 则不发放
    		if($currentMoney > $this->active_money_max){
    			return $arrs;
    		}

    		// 计算差价
    		$moneyRa = $currentMoney - $money;

    		// 如果有差价并且大于0 添加到数组
    		if($moneyRa > 0){

    			$arrs[] = array('uid' => $parent->id, 'money' => $moneyRa);

    			return $currentMoney == $this->active_money_max ? $arrs : $this->getParent($parent->parent, $currentMoney, $arrs);
    		}

    		return $this->getParent($parent->parent, $money, $arrs);

    	}else
    		return $this->getParent($parent->parent, $money, $arrs);
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
    	\App\BuserWallet::where('user_id', $uid)->increment('return_blance', $money);
    }
}
