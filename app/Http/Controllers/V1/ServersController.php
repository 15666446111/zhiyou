<?php

namespace App\Http\Controllers\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ServersController extends Controller
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
    
            $this->users   = \App\BuserParent::where('parents', 'like', "%_".$this->Users->id."_%")->pluck('user_id')->toArray();
            
            $this->users[] = $this->Users->id;
            
        }else{

            $this->users   = \App\Buser::where('parent', 'like', '%'.$this->Users->id.'%')->orWhere('id',$this->Users->id)->pluck('id')->toArray();
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


        // $arrs['Bound'] = $this->getBound();

        // $arrs['UnBound'] = $this->getUnBound();



        return $arrs;
    }

    /**
     * 查询全部机器详情信息
     */
    public function getAllMerchants()
    {
        $users = $this->users;

        $select = \App\Merchant::join('trades','merchants.merchant_sn','=','trades.merchant_sn')
        ->whereIn('user_id',$users)
        ->get()
        ->toArray();
        
        dd($select);
    }
    /**
     * 查询已绑定机器详情信息
     */
    public function getBound()
    {

        $users = $this->users;

        $select = \App\Merchant::join('trades','merchants.merchant_sn','=','trades.merchant_sn')
        ->whereIn('user_id',$users)
        ->where('bind_status',1)
        ->get()
        ->toArray();
        
        dd($select);
    }

    /**
     * 查询未绑定机器详情
     */
    public function getUnBound()
    {
        $users = $this->users;

        $select = \App\Merchant::join('trades','merchants.merchant_sn','=','trades.merchant_sn')
        ->whereIn('user_id',$users)
        ->where('bind_status',0)
        ->get()
        ->toArray();
        
        dd($select);
    }
}