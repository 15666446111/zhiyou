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

        try{
            
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

            if($User->parent != $request->user->id) return response()->json(['error'=>['message' => '用户非直接下级!']]);

            if($request->user->group == 2 && $User->group == 2){

                $arrs['trade_price']['title'] = '结算价参数设置';

                $arrs['active_price']['title'] = '激活参数设置';

                $arrs['standard_price']['title'] = '达标参数设置';

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
                        $arrs['active_price']['return_money'] = $userPolicy->default_active_set['return_money'];
                    if($User->group == 2 )
                        $arrs['active_price']['return_money'] = $userPolicy->vip_active_set['return_money'];

                    $arrs['active_price']['max'] = $this->getActivePriceMax($request->user, $policy);
                    $arrs['active_price']['min'] = 0;

                    // 读取达标返现
                    foreach ($userPolicy->standard as $key => $value) {
                        $arrs['standard_price']['list'][] = [
                            'index'         => $value['index'],
                            'standard_type' => $value['standard_type'],
                            'standard_start'=> $value['standard_start'],
                            'standard_end'  => $value['standard_end'],
                            'standard_trade'=> $value['standard_trade'] * 100,
                            'standard_agent_price' => $value['standard_agent_price'] * 100,
                            'max'           => $this->getStandardPriceMax( $policy, $request->user, $value['index'] ),
                            'min'           => 0,
                        ];
                    }
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

                    foreach ($policy->default_standard_set as $key => $value) {
                        $arrs['standard_price']['list'][] = [
                            'index'         => $value['index'],
                            'standard_type' => $value['standard_type'],
                            'standard_start'=> $value['standard_start'],
                            'standard_end'  => $value['standard_end'],
                            'standard_trade'=> $value['standard_trade'] * 100,
                            'standard_agent_price' => $value['standard_agent_price'] * 100,
                            'max'           => $this->getStandardPriceMax($policy, $request->user, $value['index'] ),
                            'min'           => 0,
                        ];
                    }
                }
            }

            return response()->json(['success'=>['message' => '获取成功!', 'data' => $arrs]]);

        } catch (\Exception $e) {

            return response()->json(['error'=>['message' => '系统错误,联系客服!']]);

        }

    }


    /**
     * @Author    Pudding
     * @DateTime  2020-06-28
     * @copyright [copyright]
     * @license   [license]
     * @version   [ 设置下级的某个政策活动的信息]
     * @param     Request     $request [description]
     */
    public function setPolicyInfo(Request $request)
    {
        try{

            if(!$request->uid) return response()->json(['error'=>['message' => '参数不全!']]);

            if(!$request->pid) return response()->json(['error'=>['message' => '参数不全!']]);

            $policyInfo = \App\Policy::where('id', $request->pid)->first();

            if(!$policyInfo or empty($policyInfo)) return response()->json(['error'=>['message' => '活动政策不存在!']]);

            /**
             * @version [<vector>] [< 获取下级用户信息。判断是否为直接下级代理>]
             */
            $son = \App\Buser::where('id', $request->uid)->first();

            if(!$son or empty($son)) return response()->json(['error'=>['message' => '用户不存在!']]);

            if($request->user->group != 2) return response()->json(['error'=>['message' => '您无权设置该选项!']]);

            if($son->parent != $request->user->id) return response()->json(['error'=>['message' => '该用户您无权设置!']]);

            if($son->group != 2) return response()->json(['error'=>['message' => '该用户不是代理!']]);

            /**
             * @version [<vector>] [< 检查完毕后 开始进行policy 设置 >]
             */
            #1 ： 首先获取该用户的该政策信息。如果没有 先增加一条默认的政策信息
            $sonPolicy = \App\UserPolicy::where('user_id', $son->id)->where('policy_id', $request->pid)->first();
            
            if(!$sonPolicy or empty($sonPolicy) or $sonPolicy == null){

                $sett_price = $policyInfo->sett_price;
                
                foreach ($sett_price as $key => $value) {
                    $sett_price[$key]['setprice'] = $value['defaultPrice'];
                }

                $default_active_set = $policyInfo->default_active_set;
                $default_active_set['return_money'] = $default_active_set['default_money'];

                $vip_active_set = $policyInfo->vip_active_set;
                $vip_active_set['return_money'] = $vip_active_set['default_money'];

                $standard = $policyInfo->default_standard_set;

                $sonPolicy = \App\UserPolicy::create([
                    'user_id'               =>  $request->uid,
                    'policy_id'             =>  $request->pid,
                    'sett_price'            =>  $sett_price,

                    'default_active_set'    => $default_active_set,
                    'vip_active_set'        => $vip_active_set,

                    'standard'              =>  $standard
                ]);
            }


            /**
             * @version [<vector>] [< 设置交易结算价 >]
             */
            if(isset($request->tradePrice)){
                #1 读取当前交易的结算价
                $sett_price = $sonPolicy->sett_price;

                foreach ($sett_price as $key => $value) {

                    $max = $this->getSetPriceMax($policyInfo, $value['trade_type'], $value['trade_bank']);
                    $min = $this->getSetPriceMin($request->user, $policyInfo, $value['trade_type'], $value['trade_bank']);
                    
                    $rate = $this->getSetPriceParams($request->tradePrice, $value['trade_name']);
                    # 说明设置过了
                    if($rate != 0 && $rate != $value['setprice']){
                        if($rate >= $min && $rate <= $max ){
                            $sett_price[$key]['setprice'] = $rate;
                        }else return response()->json(['error'=>['message' => $value['trade_name'].'参数不再合理区间内!']]);
                    }
                }

                $sonPolicy->sett_price      = $sett_price;
            }

            /**
             * @version [<vector>] [< 设置激活返现 >]
             */
            if(isset($request->activePrice)){
                #1 读取当前 Vip 结算价
                $vipActive      = $sonPolicy->vip_active_set;
                #2 获取可设置的范围
                $vipActiveMax   =  $this->getActivePriceMax($request->user, $policyInfo);
                $vipActiveMin   =  0;
                #3 传递过来设置的值 需为这两个值的区间
                if($request->activePrice * 100 >= $vipActiveMin && $request->activePrice * 100 <= $vipActiveMax ){
                    $vipActive['return_money']      = $request->activePrice * 100;
                    $sonPolicy->vip_active_set      = $vipActive;
                }else return response()->json(['error'=>['message' => '激活返现不再合理区间内!']]);
            }

            /**
             * @version [<vector>] [< 设置激活达标奖励 >]
             */
            if(isset($request->standardPrice)){
                #1 读取当前达标的信息
                $standard = $sonPolicy->standard;
                #2 循环设置
                foreach ($standard as $key => $value) {
                    $max = $this->getStandardPriceMax( $policyInfo, $request->user, $value['index'] );
                    $min = 0;
                    $price = $this->getStandardParams($request->standardPrice, $value['index']);

                    if($price != -1 ){
                        if($price * 100  >= $min && $price * 100 <= $max){
                            $standard[$key]['standard_agent_price'] = $price;
                        }else return response()->json(['error'=>['message' => '累积达标参数不再合理区间内!']]);

                    }
                }

                $sonPolicy->standard      = $standard;
            }

            $sonPolicy->save();

            return response()->json(['success'=>['message' => '设置成功!', 'data' => $sonPolicy]]);

        } catch (\Exception $e) {

            return response()->json(['error'=>['message' => '系统错误,联系客服!']]);

        }
    }


    /**
     * @Author    Pudding
     * @DateTime  2020-06-28
     * @copyright [copyright]
     * @license   [license]
     * @version   [ 设置结算价专用 返回]
     * @param     [type]      $params [description]
     * @param     [type]      $name   [description]
     * @return    [type]              [description]
     */
    public function getSetPriceParams($params, $name)
    {
        $rate = 0;

        foreach ($params as $key => $value) {
            if($value['name'] == $name) $rate = $value['rate'];
        }

        return $rate * 100;
    }

    /**
     * @Author    Pudding
     * @DateTime  2020-06-28
     * @copyright [copyright]
     * @license   [license]
     * @version   [设置达标专用]
     * @param     [type]      $params [description]
     * @param     [type]      $index  [description]
     * @return    [type]              [description]
     */
    public function getStandardParams($params, $index)
    {
        $price = -1;

        foreach ($params as $key => $value) {
            if($value['index'] == $index) $price = $value['standard_agent_price'];
        }

        return $price;
    }

    /**
     * @Author    Pudding
     * @DateTime  2020-06-24
     * @copyright [copyright]
     * @license   [license]
     * @version   [version]
     * @param     [type] $[policy] [<系统默认政策信息>]
     * @param     [type] $[user]   [< 当前登陆用户>]
     * @param     [type] $[index]  [< 当前的详细达标>]
     * @return    [type]      [description]
     */
    public function getStandardPriceMax($policy, $user, $index)
    {
        $max = 0;

        // 获取当前用户的政策信息
        $currentPolicy = \App\UserPolicy::where('user_id', $user->id)->where('policy_id', $policy->id)->first();

        if(!$currentPolicy or empty($currentPolicy)){

            foreach ($policy->default_standard_set as $key => $value) {
                if($value['index'] == $index) $max = $value['standard_agent_price'];
            }

        }else{

            foreach ($currentPolicy->standard as $key => $value) {
                if($value['index'] == $index) $max = $value['standard_agent_price'];
            }

        }


        return $max * 100;
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
