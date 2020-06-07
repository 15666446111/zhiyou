<?php

namespace App\Http\Controllers\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ServersController
{
    //

    /**
     *  查询类型 用户或者伙伴或者总数
     */
    protected $Type;

    /**
     * 当前登录会员
     */
    protected $user;


    /**
     * 查询的用户
     */
    protected $Users;


    /**
     * 初始化数据  查询条件
     */
    public function __construct($Type,$user)
    {
        $this->Type = $Type;

        $this->Users = $user;
        // dd($this->Users->id);
        if($this->Type == "user"){

            $this->users = array($this->Users->id);

        }else if($this->Type == "friends"){
    
            $this->users   = \App\BuserParent::where('parents', 'like', "%\_".$this->Users->id."\_%")->pluck('user_id')->toArray();
            // dd($this->users);
            $this->users[] = $this->Users->id;
            
        }else{

            $this->users   = \App\Buser::where('parent', 'like', '%\_'.$this->Users->id.'\_%')->orWhere('id',$this->Users->id)->pluck('id')->toArray();
            $this->users[] = $this->Users->id;

        }
        
    }


    
    /**
     * 获取终端机器管理所有信息
     */
    public function getInfo()
    {
        $arrs = [];

        $arrs['AllMerchants'] = $this->getAllMerchants();


        $arrs['Bound']        = $this->getBound();

        $arrs['UnBound']      = $this->getUnBound();

        $arrs['Bind']         = $this->getBind();



        return $arrs;
    }

    /**
     * 查询全部机器详情信息
     */
    public function getAllMerchants()
    {
        $users = $this->users;

        $select = \App\Merchant::select('merchants.merchant_sn','merchants.bind_status','merchants.active_status','merchants.active_time','merchants.bind_time','merchants.policy_id')
        ->whereIn('merchants.user_id',$users)
        ->get()
        ->toArray();
        
        foreach($select as $k=>$v){
        
            $title = \App\Policy::select('title')->where('id',$v['policy_id'])->first();

            $select[$k]['title']= $title->title;

        }
   
        return $select;
    }
    /**
     * 查询已绑定机器详情信息
     */
    public function getBound()
    {

        $users = $this->users;

        $select = \App\Merchant::select('merchants.merchant_sn','merchants.bind_status','merchants.active_status','merchants.bind_time','merchants.policy_id')
        ->whereIn('merchants.user_id',$users)
        ->where('bind_status',1)
        ->get()
        ->toArray();

        foreach($select as $k=>$v){
        
            $title = \App\Policy::select('title')->where('id',$v['policy_id'])->first();

            $select[$k]['title']= $title->title;

        }
        
        return $select;
    }

    /**
     * 查询未绑定机器详情
     */
    public function getUnBound()
    {
        $users = $this->users;

        $select = \App\Merchant::select('merchants.merchant_sn','merchants.bind_status','merchants.active_status','merchants.policy_id')
        ->whereIn('merchants.user_id',$users)
        ->where('bind_status',0)
        ->get()
        ->toArray();

        foreach($select as $k=>$v){
        
            $title = \App\Policy::select('title')->where('id',$v['policy_id'])->first();

            $select[$k]['title']= $title->title;

        }
        
        return $select;
    }

    /**
     * 查询已激活机器详情
     */
    public function getBind()
    {
        $users = $this->users;

        $select = \App\Merchant::select('merchants.merchant_sn','merchants.active_status','merchants.policy_id')
        ->whereIn('merchants.user_id',$users)
        ->where('active_status',1)
        ->get()
        ->toArray();

        foreach($select as $k=>$v){
        
            $title = \App\Policy::select('title')->where('id',$v['policy_id'])->first();

            $select[$k]['title']= $title->title;

        }
        
        return $select;
    }
}
