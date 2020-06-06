<?php

namespace App\Http\Controllers\V1;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MerchantsController extends Controller
{
    
    /**
     * 首页商户登记绑定接口
     */
    public function registers(Request $request)
    {
        try{ 
             
            \App\Merchant::where('user_id',$request->user->id)->where('merchant_terminal',$request->merchant_terminal)->update([
                'merchant_name'=>$request->merchant_name,
                'user_phone'=>$request->merchant_phone,
                'bind_status'=>1
            ]);
            
            return response()->json(['success'=>['message' => '登记成功!', []]]); 

    	} catch (\Exception $e) {
            
            return response()->json(['error'=>['message' => '系统错误,联系客服!']]);

        }
    }

    /**
     * 商户列表接口
     */
    public function merchantsList(Request $request)
    {
        try{ 
            
            $Bound=\App\Merchant::select('merchants.id','merchants.merchant_name','merchant_number','merchants.merchant_sn','money','merchants.created_at','merchants.bind_time','active_time')
            ->join('trades','trades.terminal','=','merchants.merchant_terminal')
            ->where(['user_id'=>$request->user->id,'bind_status'=>1]) 
            ->get()
            ->toArray();

            foreach($Bound as $k=>$v){

                $UnBound[$k]['time'] = $v['bind_time'] ? $v['bind_time'] : $v['active_time'];

            }
            $item = array();
            foreach($Bound as $k=>$v){
                
                if(!isset($item[$v['id']])){

                    $item[$v['id']]=$v;

                }else{

                    $item[$v['id']]['money']+=$v['money'];

                }

            }
            
            $data=[];
            $data['Bound']=$item;
            
            $UnBound=\App\Merchant::select('merchants.id','merchants.merchant_name','merchant_number','merchants.merchant_sn','money','merchants.created_at','merchants.bind_time','active_time')
            ->join('trades','trades.terminal','=','merchants.merchant_terminal')
            ->where(['user_id'=>$request->user->id,'bind_status'=>0]) 
            ->get()
            ->toArray();
            
            foreach($UnBound as $k=>$v){

                $UnBound[$k]['time'] = $v['bind_time'] ? $v['bind_time'] : $v['active_time'];

            }
            
            $items = array();
            foreach($UnBound as $k=>$v){

                if(!isset($items[$v['id']])){

                    $items[$v['id']]=$v;

                }else{

                    $items[$v['id']]['money']+=$v['money'];

                }
                

            }

            $data['UnBound']=$items;
            
            return response()->json(['success'=>['message' => '获取成功!', 'data' => $data]]); 

    	} catch (\Exception $e) {
            
            return response()->json(['error'=>['message' => $e->getMessage()]]);

        }
    }

    /**
     * 个人商户详情接口
     */
    public function merchantInfo(Request $request)
    {
        try{ 
             
            $data=\App\Merchant::where('user_id',$request->user->id)
            ->where('id',$request->id)
            ->first();
            
            $data['time'] = $data->bind_time ? $data->bind_time : $data->active_time;

            
            return response()->json(['success'=>['message' => '获取成功!', 'data' => $data]]);   

    	} catch (\Exception $e) {
            
            return response()->json(['error'=>['message' => '系统错误，请联系客服']]);

        }
    }

    /**
     * 商户交易明细
     */
    public function MerchantDetails(Request $request)
    {
        //参数 终端号
        $merchant = $request->merchant;

        $dateType   = $request->data_type ?? 'day';

        if($dateType == 'day'){
            $date  		= $request->date ?? Carbon::today()->toDateTimeString();
        }else
            $date       = $request->date ?? Carbon::today()->toDateTimeString();

        if(!$merchant){
            return response()->json(['error'=>['message' => '终端号无效']]);
        }

        $server = new \App\Http\Controllers\V1\MerchantMoneyController($merchant,$dateType,$date);

        $data   = $server->getInfo();

		return response()->json(['success'=>['message' => '获取成功!', 'data'=>$data]]);

    }


    /**
     * @Author    Pudding
     * @DateTime  2020-06-06
     * @copyright [copyright]
     * @license   [license]
     * @version   [获取代理的商户分布情况。返回代理个人商户 与 代理商户情况]
     * @param     Request     $request [description]
     * @return    [type]               [description]
     */
    public function getAgentDetail(Request $request)
    {
        if(!$request->uid) return response()->json(['error'=>['message' => '无效参数']]);

        $arrs = array();

        // 获取代理
        $team = \App\BuserParent::where('parents', 'like', '%_'.$request->uid.'_%')->pluck('user_id')->toArray();

        $arrs['agent']  = \App\Merchant::whereIn('user_id', $team)->count();

        // 获取个人商户情况
        $arrs['me'] = \App\Merchant::where('user_id', $request->uid)->count();

        return response()->json(['success'=>['message' => '获取成功!', 'data'=>$arrs]]);

    }
}
