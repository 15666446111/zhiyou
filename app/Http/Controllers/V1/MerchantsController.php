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
                'merchant_name'     => $request->merchant_name,
                'user_phone'        => $request->merchant_phone,
                'bind_status'       => 1,
                'bind_time'         => Carbon::now()->toDateTimeString(),
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

            $arrs;
            
            $bind = \App\Merchant::where('user_id', $request->user->id)->where('bind_status', '1')->get();


            foreach ($bind as $key => $value) {
                $arrs['Bound'][] = array(
                    'id'                =>  $value->id,
                    'merchant_name'     =>  $value->merchant_name,
                    'merchant_number'   =>  $value->merchant_number,
                    'merchant_sn'       =>  $value->merchant_sn,
                    'money'             =>  $value->tradess->sum('money'),
                    'created_at'        =>  $value->created_at,
                    'bind_time'         =>  $value->bind_time,
                    'active_time'       =>  $value->active_time,
                    'time'              =>  $value->bind_time ?? $value->active_time
                );
            }
            
            
            $UnBind =\App\Merchant::where('user_id', $request->user->id)->where('bind_status', '0')->get();
            
            foreach ($UnBind as $key => $value) {
                $arrs['UnBound'][] = array(
                    'id'                =>  $value->id,
                    'merchant_name'     =>  $value->merchant_name,
                    'merchant_number'   =>  $value->merchant_number,
                    'merchant_sn'       =>  $value->merchant_sn,
                    'money'             =>  $value->tradess->sum('money'),
                    'created_at'        =>  $value->created_at,
                    'bind_time'         =>  $value->bind_time,
                    'active_time'       =>  $value->active_time,
                    'time'              =>  $value->bind_time ?? $value->active_time
                );
            }
            
            return response()->json(['success'=>['message' => '获取成功!', 'data' => $arrs]]); 

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



}
