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
            $cashInfo=\App\Merchant::leftJoin('cashs','cashs.user_id','=','merchants.user_id')
                            ->where('cashs.user_id',$request->user->id) 
                            ->get()
                            ->toArray();  
            dd($cashInfo);
            foreach($cashInfo as $k=>$v){  
                
                $cashInfo[$k]['created_time']=$v['created_at'];

                $cashInfo[$k]['created_at']=strtotime($v['created_at']);

                //删除无用的字段
                unset($cashInfo[$k]['user_phone']);

                unset($cashInfo[$k]['merchant_number']);

                unset($cashInfo[$k]['merchant_terminal']);

                unset($cashInfo[$k]['active_status']);

                unset($cashInfo[$k]['brand_id']);

                unset($cashInfo[$k]['policy_id']);

                unset($cashInfo[$k]['merchant_name']);

                unset($cashInfo[$k]['bind_status']);

                unset($cashInfo[$k]['bind_time']);

                unset($cashInfo[$k]['active_time']);

                unset($cashInfo[$k]['standard_statis']);

            }

            //根据日期进行分组
            $curyear = date('Y'); 

            $visit_list = [];

            foreach ($cashInfo as $v) {

                if ($curyear == date('Y', $v['created_at'])) {

                    $date = date('Y年m月d日', $v['created_at']);

                }

                $data[$date][] = $v;
            }
            dd($data);
            return response()->json(['success'=>['message' => '获取成功!', 'data' => $data]]); 

    	} catch (\Exception $e) {
            
            return response()->json(['error'=>['message' => $e->getMessage()]]);

        }
    }
}
