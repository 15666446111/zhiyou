<?php

namespace App\Http\Controllers\V1;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ApplicationController extends Controller
{
    //
    /**
     * @Author    Pudding
     * @DateTime  2020-06-28
     * @copyright [copyright]
     * @license   [license]
     * @version   [获取朋友圈申请的机器列表]
     * @param     Request     $request [description]
     * @return    [type]               [description]
     */
    public function list(Request $request)
    {
    	try{
            // 获取展示的轮播图
            $list = \App\ApplicationForm::where('user_id', $request->user->id)
            			->orWhere('agent_id', $request->user->id)->orderBy('id', 'desc')->get();

            return response()->json(['success'=>['message' => '获取成功!', 'data' => $list]]);

    	} catch (\Exception $e) {

            return response()->json(['error'=>['message' => '系统错误,联系客服!']]);

        }
    }


    /**
     * @Author    Pudding
     * @DateTime  2020-06-28
     * @copyright [copyright]
     * @license   [license]
     * @version   [ 确定装机 ]
     * @param     Request     $request [description]
     */
    public function set(Request $request)
    {
        try{

            if(!$request->order_id) return response()->json(['error'=>['message' => '缺少必要参数:order_id!']]);

            if(!$request->sn) return response()->json(['error'=>['message' => '缺少必要参数:机器SN!']]);

            $order = \App\ApplicationForm::where('id', $request->order_id)->first();

            if(!$order or empty($order)) return response()->json(['error'=>['message' => '申请订单不存在!']]);

            // 获取该SN信息
            $temail = \App\Merchant::where('user_id', $request->sn)->where('bind_status', 0)->where('active_status', 0)->first();

            if(!$temail or empty( $temail ))
                 return response()->json(['error'=>['message' => '该终端不存在或已绑定/激活!']]);


            if($order->agent_id != $request->user->id) return response()->json(['error'=>['message' => '非法授权!']]);

            if($order->is_handle != 0 ) return response()->json(['error'=>['message' => '该申请已处理!']]);

            $order->is_handle = 1;
            $order->handle_time = Carbon::now()->toDateTimeString();
            $order->handle_temail = $request->sn;
            $order->save();

            return response()->json(['success'=>['message' => '装机成功!', 'data' => $order]]);

        } catch (\Exception $e) {

            return response()->json(['error'=>['message' => '系统错误,联系客服!']]);

        }
    }
}
