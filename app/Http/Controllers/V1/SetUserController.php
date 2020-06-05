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
     * 添加银行卡结算信息接口
     */
    public function insertBank(Request $request)
    {
        try{ 
            
            \App\Bank::create([
                'user_id'=>$request->user->id,
                'name'=>$request->name,
                'bank_name'=>$request->bank_name,
                'bank'=>$request->bank,
                'number'=>$request->number,
                'open_bank'=>$request->open_bank,
                'is_del'=>0,
                'is_default'=>0
            ]);

            return response()->json(['success'=>['message' => '添加成功!', []]]); 

    	} catch (\Exception $e) {
            
            return response()->json(['error'=>['message' => '系统错误,联系客服!']]);

        }
    }

    /**
     * 查询银行卡结算信息接口
     */
    public function selectBank(Request $request)
    {
        try{ 
            
            $data=\App\Bank::where('user_id',$request->user->id)
            ->where('is_del',0)
            ->orderBy('is_default','desc')
            ->get();

            return response()->json(['success'=>['message' => '获取成功!', 'data'=>$data]]); 

    	} catch (\Exception $e) {
            
            return response()->json(['error'=>['message' => '系统错误,联系客服!']]);

        }
    }

    /**
     * 查询单个银行卡信息接口
     */
    public function bankFirst(Request $request)
    {
        try{ 
            
            $data=\App\Bank::where('user_id',$request->user->id)
                            ->where('id',$request->id)
                            ->get();

            return response()->json(['success'=>['message' => '获取成功!', 'data'=>$data]]); 

    	} catch (\Exception $e) {
            
            return response()->json(['error'=>['message' => '系统错误,联系客服!']]);

        }
    }


    /**
     * 删除银行卡结算信息接口
     */
    public function unsetBank(Request $request)
    {
        try{ 
            
            \App\Bank::where('user_id',$request->user->id)->update(['is_del'=>1]);

            return response()->json(['success'=>['message' => '删除成功!', []]]); 

    	} catch (\Exception $e) {
            
            return response()->json(['error'=>['message' => '系统错误,联系客服!']]);

        }
    }

    /**
     * 修改银行卡结算信息接口
     */
    public function updateBank(Request $request)
    {
        try{ 

            if(!$request->is_default){

                \App\Bank::where('user_id',$request->user->id)->where('id',$request->id)->update([
                    'name'=>$request->name,
                    'bank_name'=>$request->bank_name, 
                    'bank'=>$request->bank,
                    'number'=>$request->number,
                    'open_bank'=>$request->open_bank,
                    'is_default'=>0
                ]);

            }else{

                \App\Bank::where('user_id',$request->user->id)->where('id',$request->id)->update(['is_default'=>0]);

                \App\Bank::where('user_id',$request->user->id)->where('id',$request->id)->update([
                    'name'=>$request->name,
                    'bank_name'=>$request->bank_name, 
                    'bank'=>$request->bank,
                    'number'=>$request->number,
                    'open_bank'=>$request->open_bank,
                    'is_default'=>1
                ]);

            }
            


            return response()->json(['success'=>['message' => '修改成功!', []]]); 


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

            $checkDayStr = date('Y-m-d ',time());
            $timeBegin1 = strtotime($checkDayStr."09:00".":00");
            $timeEnd1 = strtotime($checkDayStr."21:00".":00");
            
            $curr_time = time();

            //判断是否在这个时间段内提现
            if($curr_time >= $timeBegin1 && $curr_time <= $timeEnd1)
            {

                if($request->money<200){

                    return response()->json(['error'=>['message' => '提现金额必须不低于200元']]);
    
                }
    
                //判断钱包类型
                if($request->blance='1'){
    
                    $info=\App\BuserWallet::where('blance_active',$request->blance)->first();
                    
                    $user_money=$info['cash_blance'];
                    
    
                }else{
                    $info=\App\BuserWallet::where('blance_active',$request->blance)->first();
    
                    $user_money=$info['return_blance'];
                }
    
                if($user_money<$request->money){
    
                    return response()->json(['error'=>['message' => '提现金额错误']]);
    
                }
    
                \App\Withdraw::where('user_id',$request->user->id)->create([
                    'user_id'=>$request->user->id,
                    'money'=>$request->money,
                    'rate'=>$request->rate,
                    'rate_money'=>$request->money * $request->rate,
                    'status'=>0,
                    'pay_time'=>date('Y-m-d H:i:s',time()),
                    'remark'=>$request->remark
                ]);
    
                return response()->json(['success'=>['message' => '提现申请提交成功!', []]]);
            }else{   
                
                return response()->json(['error'=>['message' => '请在规定时间提现哦']]);

            }

             


    	} catch (\Exception $e) {
            
            return response()->json(['error'=>['message' => '系统错误,联系客服!']]);

        }

    }

    /**
     * 提现税点接口
     */
    public function point(Request $request)
    {

        try{ 
            // dd(config('draw.rate'));
            //获取提现税点
            $data['point']=config('draw.rate');
            //最小提现金额
            $data['min_money']=200;
            //提现范围时间
            $data['point_time']='9:00~21:00';

            return response()->json(['success'=>['message' => '获取成功!', 'data'=>$data]]); 


    	} catch (\Exception $e) {
            
            return response()->json(['error'=>['message' => '系统错误,联系客服!']]);

        }

    }


}
