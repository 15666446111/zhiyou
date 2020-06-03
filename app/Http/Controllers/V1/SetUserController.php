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
                'open_bank'=>$request->open_bank
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
            
            $data=\App\Bank::where('user_id',$request->user->id)->where('is_del',0)->get();


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
            
            \App\Bank::where('user_id',$request->user->id)->update([
                'name'=>$request->name,
                'bank_name'=>$request->bank_name, 
                'bank'=>$request->bank,
                'number'=>$request->number,
                'open_bank'=>$request->open_bank
            ]);


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

            if($request->money<200){
                return response()->json(['error'=>['message' => '提现金额必须不低于200元']]);
            }
            
            if($request->blance='cash'){
                $info=\App\BuserWallet::where('cash_blance',$request->blance)->get();
                $money=$info['cash_blance'];
            }

            if($request->blance='return'){
                $info=\App\BuserWallet::where('return_blance',$request->blance)->get();
                $money=$info['return_blance'];
            }

            if($money!=$request->user_money){
                return response()->json(['error'=>['message' => '提现金额错误']]);
            }

            \App\Withdraw::where('user_id',$request->user->id)->create([
                'user_id'=>$request->user->id,
                'money'=>$request->money,
                'status'=>0,
                'pay_time'=>date('Y-m-d H:i:s',time()),
                'remark'=>$request->remark
            ]);

            return response()->json(['success'=>['message' => '提现申请提交成功!', []]]); 


    	} catch (\Exception $e) {
            
            return response()->json(['error'=>['message' => '系统错误,联系客服!']]);

        }

    }


}
