<?php

namespace App\Http\Controllers\V1;

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
            
            return response()->json(['error'=>['message' => $e->getMessage()]]);

        }
    }
}
