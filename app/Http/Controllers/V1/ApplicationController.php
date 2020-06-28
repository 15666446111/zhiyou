<?php

namespace App\Http\Controllers\V1;

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
}
