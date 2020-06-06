<?php

namespace App\Http\Controllers\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TransferController extends Controller
{
    
    /**
     * 查询用户未绑定终端机器
     */
    public function getUnBound(Request $request)
    {

        try{
            $list = \App\Merchant::where('user_id',$request->user->id)
            ->where('active_status',0)
            ->where('bind_status',0)
            ->get()->toArray();

            return response()->json(['success'=>['message' => '获取成功!', 'data'=>$list]]);
        
        } catch (\Exception $e) {
            
            return response()->json(['error'=>['message' => '系统错误,联系客服!']]);

        }

    }
}
