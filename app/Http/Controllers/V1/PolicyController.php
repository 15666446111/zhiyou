<?php

namespace App\Http\Controllers\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PolicyController extends Controller
{
    //
    /**
     * @Author    Pudding
     * @DateTime  2020-06-02
     * @copyright [copyright]
     * @license   [license]
     * @version   [获取政策活动列表]
     * @param     Request     $request [description]
     * @return    [type]               [description]
     */
    public function getPolicy(Request $request)
    {
    	try{
            // 获取展示的轮播图
            $policy = \App\Policy::select(['id', 'title'])->get();

            return response()->json(['success'=>['message' => '获取成功!', 'data' => $policy]]);

    	} catch (\Exception $e) {

            return response()->json(['error'=>['message' => '系统错误,联系客服!']]);

        }
    }

    /**
     * @Author    Pudding
     * @DateTime  2020-06-03
     * @copyright [copyright]
     * @license   [license]
     * @version   [获取用户的政策信息]
     * @param     Request     $request [description]
     * @return    [type]               [description]
     */
    public function getPolicyInfo(Request $request)
    {
        if(!$request->uid) return response()->json(['error'=>['message' => '参数不全!']]);

        if(!$request->pid) return response()->json(['error'=>['message' => '参数不全!']]);

        /**
         * 获取该用户的该政策信息
         */
        $User = \App\Buser::where('id', $request->uid)->first();
        if(!$User or empty($User)) return response()->json(['error'=>['message' => '用户不存在!']]);

        //
        $policy = \App\Policy::where('id', $request->pid)->first();
        if(!$policy or empty($policy)) return response()->json(['error'=>['message' => '政策活动不存在!']]);

        // 获取该用户的政策活动
        $userPolicy = \App\UserPolicy::where('user_id', $request->uid)->where('policy_id', $request->pid)->first();

        // 组合返回数据
        $arrs = [];

        $arrs['trade_price']['title'] = '结算价参数设置';

        $arrs['active_price']['title'] = '激活参数设置';

        if($userPolicy && !empty($userPolicy)){
            // 设置结算价
            foreach ($userPolicy->sett_price as $key => $value) {
                $arrs['trade_price']['list'][] = [
                    'name' => $value['trade_name'],
                    'rate' => $value['setprice'],
                    'max'  => $this->getSetPriceMax($policy, $value['trade_type'], $value['trade_bank']),
                    'min'  => $this->getSetPriceMin($request->user, $policy, $value['trade_type'], $value['trade_bank']),
                ];
            }
            // 设置激活返现
            if($User->group == 1 )
                $arrs['active_price']['money'] = $userPolicy->default_active_set['return_money'];
            if($User->group == 2 )
                $arrs['active_price']['money'] = $userPolicy->vip_active_set['return_money'];

            $arrs['active_price']['max'] = $this->getActivePriceMax($request->user, $policy);
            $arrs['active_price']['min'] = 0;
        }else{
            // 设置结算价
            foreach ($policy->sett_price as $key => $value) {
                $arrs['trade_price']['list'][] = [
                    'name' => $value['trade_name'],
                    'rate' => $value['defaultPrice'],
                    'max'  => $this->getSetPriceMax($policy, $value['trade_type'], $value['trade_bank']),
                    'min'  => $this->getSetPriceMin($request->user, $policy, $value['trade_type'], $value['trade_bank']),   
                ];
            }

            // 设置激活返现参数
            if($User->group == 1 )
                $arrs['active_price']['return_money'] = $policy->default_active_set['default_money'];
            if($User->group == 2 )
                $arrs['active_price']['return_money'] = $policy->vip_active_set['default_money'];

            $arrs['active_price']['max'] = $this->getActivePriceMax($request->user, $policy);
            $arrs['active_price']['min'] = 0;
        }

        return response()->json(['success'=>['message' => '获取成功!', 'data' => $arrs]]);

    }


    /**
     * @Author    Pudding
     * @DateTime  2020-06-03
     * @copyright [copyright]
     * @license   [license]
     * @version   [获取用户的最高返现金额]
     * @param     [type]      $user   [description]
     * @param     [type]      $policy [description]
     * @return    [type]              [description]
     */
    public function getActivePriceMax($user, $policy)
    {
        $active_money_max = 0;
        // 获取用户在该政策的结算价配置
        $userPolicy = \App\UserPolicy::where('user_id', $user->id)->where('policy_id', $policy->id)->first();

        if(!$userPolicy or empty($userPolicy)){
            if($user->group == "1"){
                $active_money_max = $policy->default_active_set['default_money'];
            }

            if($user->group == "2"){
                $active_money_max = $policy->vip_active_set['default_money'];
            }
        } else {
            if($user->group == "1"){
                $active_money_max = $userPolicy->default_active_set['return_money'];
            }

            if($user->group == "2"){
                $active_money_max = $userPolicy->vip_active_set['return_money'];
            }
        }
        
        return $active_money_max;
    }


    /**
     * @Author    Pudding
     * @DateTime  2020-06-03
     * @copyright [copyright]
     * @license   [license]
     * @version   [获取结算价的最高可设置值]
     * @param     [type]      $policy  [policy模型]
     * @return    [type]           [description]
     */
    public function getSetPriceMax($policy, $trade_type, $bank_type)
    {
        $userRate = 0 ;

        foreach ($policy->sett_price as $key => $value) {
            if($value['trade_type'] == $trade_type && $value['trade_bank'] == $bank_type){
                $userRate = $value['defaultPrice'];
            }   
        }

        return $userRate;
    }





    /**
     * @Author    Pudding
     * @DateTime  2020-06-03
     * @copyright [copyright]
     * @license   [license]
     * @version   [获取结算价的最高可设置值]
     * @param     [type]      $user    [userm模型]
     * @param     [type]      $policy  [policy模型]
     * @return    [type]           [description]
     */
    public function getSetPriceMin($user, $policy, $trade_type, $bank_type)
    {
        // 获取用户在该政策的结算价配置
        $userPolicy = \App\UserPolicy::where('user_id', $user->id)->where('policy_id', $policy->id)->first();

        if(!$userPolicy or empty($userPolicy)){
            foreach ($policy->sett_price as $key => $value) {
                if($value['trade_type'] == $trade_type && $value['trade_bank'] == $bank_type){
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
            if($value['trade_type'] == $trade_type && $value['trade_bank'] == $bank_type){
                $userRate = $value['setprice'];
            }       
        }

        return $userRate;

    }







}
