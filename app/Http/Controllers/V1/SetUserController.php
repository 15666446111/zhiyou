<?php

namespace App\Http\Controllers\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SetUserController extends Controller
{
    /**
     * 修改个人登录密码接口
     */
    public function updatePwd(Request $request)
    { 
        try{ 
            
            $User = \App\Buser::where('id', $request->user->id)->first();
            
            if($User->password !=  md5($request->password)) 
                return response()->json(['error'=>['message' => '账号密码错误']]);

            $data=\App\Buser::where('id',$request->user->id)
                            ->where('password',md5($request->password))
                            ->update(['password'=>md5($request->newPassword)]);

            if($data){

                return response()->json(['success'=>['message' => '修改成功!', []]]); 

            }

    	} catch (\Exception $e) {
            
            return response()->json(['error'=>['message' => '系统错误,联系客服!']]);

        }
    }



    /**
     * 用户提现接口
     */
    public function Withdrawal(Request $request)
    {
        try{ 

            if($request->user->wallets->blance_active !="1"){
                return response()->json(['error'=>['message' => $request->user->wallets->blance_bak]]);
            }

            $checkDayStr = date('Y-m-d ',time());
            $timeBegin1  = strtotime($checkDayStr."09:00".":00");
            $timeEnd1    = strtotime($checkDayStr."21:00".":00");
            
            $curr_time   = time();
            
            //判断是否在这个时间段内提现
            if($curr_time >= $timeBegin1 && $curr_time <= $timeEnd1)
            {

                if($request->money < 20000 ){

                    return response()->json(['error'=>['message' => '提现金额必须不低于200元']]);
    
                }
        
                //判断钱包类型
                if($request->blance =='1'){
                    
                    if($request->user->wallets->cash_blance < $request->money ){
                        
                        return response()->json(['error'=>['message' => '当前钱包余额不足']]);
                    }

                    $request->user->wallets->cash_blance = $request->user->wallets->cash_blance - $request->money;
    
                }else{

                    if($request->user->wallets->return_blance < $request->money ){
                        return response()->json(['error'=>['message' => '当前钱包余额不足']]);
                    }

                    $request->user->wallets->return_blance = $request->user->wallets->return_blance - $request->money;
                }

                $request->user->wallets->save();
                
                
                \App\Withdraw::create([
                    'user_id'   => $request->user->id,
                    'money'     => $request->money,
                    'rate'      => $request->rate,
                    'real_money'=> $request->money - $request->money * $request->rate - $request->rate_m,
                    'rate_money'=> $request->money * $request->rate,
                    'single_rate'=>$request->rate_m,
                    'blance'    => $request->blance,
                    'bank'      => $request->bank,
                    'name'      => $request->name,
                    'number'    => $request->number,
                    'open_bank' => $request->open_bank,
                    'bank_name' => $request->bank_name
                ]);
    
                return response()->json(['success'=>['message' => '提现申请提交成功!', 'data' => $request->user->wallets]]);

            }else{   
                
                return response()->json(['error'=>['message' => '请在规定时间提现哦']]);

            }


    	} catch (\Exception $e) {
            
            return response()->json(['error'=>['message' => '系统错误,联系客服!']]);

        }

    }

    /**
     * @Author    Pudding
     * @DateTime  2020-07-23
     * @copyright [copyright]
     * @license   [license]
     * @version   [ 我的 - 提现 - 获取提现税点]
     * @param     Request     $request [description]
     * @return    [type]               [description]
     */
    public function point(Request $request)
    {

        try{ 

            // 判断是分润钱包还是返现钱包 * 获取提现税点
            if($request->type == '1'){
                //税点
                $data['point']=config('draw.rate');
                //单笔提现费  
                $data['rate_m']= number_format(config('draw.rate_m') / 100, 2, '.', ',');
                //免审核额度
                $data['no_check']=config('draw.no_check');
                // 最小提现金额
                $data['min_money'] = number_format(config('draw.cash_min') / 100, 2, '.', ',');
            }else{  
                $data['point']=config('draw.return_blance');

                $data['rate_m']=number_format(config('draw.return_money') / 100, 2, '.', ',');

                $data['no_check']=config('draw.no_check');

                $data['min_money'] = number_format(config('draw.return_min') / 100, 2, '.', ',');
            }
            //提现范围时间
            $data['point_time']='9:00~21:00';

            return response()->json(['success'=>['message' => '获取成功!', 'data'=>$data]]); 


    	} catch (\Exception $e) {
            
            return response()->json(['error'=>['message' => '系统错误,联系客服!']]);

        }

    }


}
