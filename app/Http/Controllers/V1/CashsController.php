<?php

namespace App\Http\Controllers\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CashsController extends Controller
{
    /**
     * 收益页面接口
     */
    public function cashsIndex(Request $request)
    {
        try{ 
            
            //总收益
            $dataAll = \App\Cash::select('cash_money')
            ->where('user_id',$request->user->id)
            ->sum('cash_money');

            //今日收益
            $dataToday = \App\Cash::select('cash_money')
            ->where('user_id',$request->user->id)
            ->whereDate('created_at', date('Y-m-d',time()))
            ->sum('cash_money');

            //本月收益
            $data1 = \App\Cash::select('cash_money')
            ->where('user_id',$request->user->id)
            ->whereBetween('created_at', [date('Y-m-01',time()),date('Y-m-t',time())])
            ->sum('cash_money');

            //查询用户账号余额
            $res=\App\BuserWallet::where('user_id',$request->user->id)->get();
            foreach($res as $key=>$value){
                $data['a']=$value['cash_blance']+$value['return_blance'];   
            }

            $list=\App\Cash::get();
            foreach($list as $k=>$v){
                dd($v->id);
            }

            dd($dataAll);
            return response()->json(['success'=>['message' => '获取成功!', 'data' => $data]]); 

    	} catch (\Exception $e) {
            
            return response()->json(['error'=>['message' => $e->getMessage()]]);

        }
    }
}
