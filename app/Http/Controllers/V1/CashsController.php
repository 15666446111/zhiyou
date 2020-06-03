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
            $data['revenueAll'] = \App\Cash::select('cash_money')
            ->where('user_id',$request->user->id)
            ->sum('cash_money');

            //今日收益
            $data['revenueDay'] = \App\Cash::select('cash_money')
            ->where('user_id',$request->user->id)
            ->whereDate('created_at', date('Y-m-d',time()))
            ->sum('cash_money');

            //本月收益
            $data['revenueMonth'] = \App\Cash::select('cash_money')
            ->where('user_id',$request->user->id)
            ->whereBetween('created_at', [date('Y-m-01',time()),date('Y-m-t',time())])
            ->sum('cash_money');

            // 查询用户账号余额
            $res=\App\BuserWallet::where('user_id',$request->user->id)->get();

            foreach($res as $key=>$value){

                $data['balance']=$value['cash_blance']+$value['return_blance'];   

            }

            //查询日期收益详情
            $cashInfo=\App\cash::select('cashs.id','cashs.created_at','cash_money','cash_type','merchants.merchant_sn','price')
                            ->Join('merchants','merchants.user_id','=','cashs.user_id')
                            ->leftJoin('orders','orders.order_no','=','cashs.order')
                            ->where('cashs.user_id',$request->user->id) 
                            ->orderByDesc('cashs.created_at')
                            ->get()
                            ->toArray();  
            
            foreach($cashInfo as $k=>$v){  

                $id=$v['id'];

                $info[$id]=$v;

            }

            foreach($info as $k=>$v){

                $info[$k]['created_time']=$v['created_at'];

                $info[$k]['created_at']=strtotime($v['created_at']);

            }

            //根据日期进行分组
            $curyear = date('Y'); 

            $visit_list = [];

            $weekarray=array("日","一","二","三","四","五","六");

            foreach ($info as $key=>$value) {
                
                if ($curyear == date('Y', $value['created_at'])) {
                    
                    $date = date('m月d日'.'星期'.$weekarray[date('w',$value['created_at'])], $value['created_at']);

                }

                $data['cash'][$date][] = $value;
                
            }
            
            return response()->json(['success'=>['message' => '获取成功!', 'data' => $data]]); 

    	} catch (\Exception $e) {
            
            return response()->json(['error'=>['message' => '提现金额错误']]);

        }
    }
}
