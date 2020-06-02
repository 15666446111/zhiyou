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
}
