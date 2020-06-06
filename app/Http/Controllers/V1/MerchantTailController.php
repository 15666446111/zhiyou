<?php

namespace App\Http\Controllers\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MerchantTailController extends Controller
{
    //

    /**
     * 机具详情接口
     */
    public function getMerchantsTail(Request $request)
    {
        try{

            //参数 friends伙伴  count总  user用户
            $Type = $request->Type;
            // dd($Type);
            $server = new \App\Http\Controllers\V1\ServersController($Type, $request->user);

            $data = $server->getInfo();

            return response()->json(['success'=>['message' => '获取成功!', 'data'=>$data]]); 

        } catch (\Exception $e) {

			return response()->json(['error'=>['message' => $e->getMessage()]]);
		
		}

    }
}
