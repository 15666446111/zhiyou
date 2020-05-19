<?php

namespace App\Http\Controllers\V1;

use App\BuserMessage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MessageController extends Controller
{
	/**
	 * @Author    Pudding
	 * @DateTime  2020-05-16
	 * @copyright [copyright]
	 * @license   [license]
	 * @version   [ app 获取消息信息接口 ]
	 * @param     Request type  获取的消息类型
	 * @return    [type]
	 */
    public function getMessage(Request $request)
    {
    	try{
    		// 如果不指定type类型 默认获取其他消息
    		$request->type  = $request->type ?? 'Other';

            $message =  BuserMessage::where('user_id', $request->user->id)
            				->where('type', $request->type)->orderBy('id', 'desc')->offset(0)->limit(15)->get();
            				
           	return response()->json(['success'=>['message' => '获取成功!', 'data' => $message]]);

    	} catch (\Exception $e) {
            
            return response()->json(['error'=>['message' => '系统错误,联系客服!']]);

        }
    }
}
