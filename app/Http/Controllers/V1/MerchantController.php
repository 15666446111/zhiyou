<?php

namespace App\Http\Controllers\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MerchantController extends Controller
{
    
	/**
	 * @Author    Pudding
	 * @DateTime  2020-05-22
	 * @copyright [商户登记 获取用户所有未登记的机器列表]
	 * @license   [license]
	 * @version   [version]
	 * @param     Request     $request [description]
	 * @return    [type]               [description]
	 */
    public function getNoBindList(Request $request)
    {
    	try{
			
            $merchant = \App\Merchant::select('merchant_terminal')->where('user_id', $request->user->id)->where('bind_status', '0')->get();
            				
           	return response()->json(['success'=>['message' => '获取成功!', 'data' => $merchant]]);

    	} catch (\Exception $e) {
            
            return response()->json(['error'=>['message' => '系统错误,联系客服!']]);

        }
	}
	
	/**
	 * 机具管理页面接口
	 */
	public function getBind(Request $request)
	{

		try{
			//获取用户机器总数
			$data['all'] = \App\Merchant::where('user_id', $request->user->id)->count();
			//获取用户未绑定机器总数
			$data['NoMerchant'] = \App\Merchant::where('user_id', $request->user->id)->where('bind_status', '0')->count();
			//查询用户已绑定机器总数
			$data['Merchant'] = \App\Merchant::where('user_id', $request->user->id)->where('bind_status', '1')->count();
			//查询用户已激活机器总数
			$data['Merchant_status'] = \App\Merchant::where('user_id', $request->user->id)->where('active_status', '1')->count();
			//查询用户已达标机器总数
			$data['standard_statis'] = \App\Merchant::where('user_id', $request->user->id)->where('standard_statis', '1')->count();
            
           	return response()->json(['success'=>['message' => '获取成功!', 'data' => $data]]);

    	} catch (\Exception $e) {
            
            return response()->json(['error'=>['message' => '系统错误,联系客服!']]);

		}
		
	}
}
