<?php

namespace App\Http\Controllers\V1;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;

class LoginController extends Controller
{	
	/**
	 * @version  [<用户登录API接口>]
	 * @author Pudding   
	 * @DateTime 2020-04-08T17:17:40+0800
	 * @param    Request
	 * @return   [type]
	 */
    public function login(LoginRequest $request)
    {
    	try{

    		$User = \App\Buser::where('account', $request->account)->first();
    		
            if($User->password !=  md5($request->password)) 
                return response()->json(['error'=>['message' => '账号密码错误']]);

            if($User->active < 1) 
                return response()->json(['error'=>['message' => '用户访问受限']]); 

            $User->last_ip  =   $request->getClientIp();

            $User->last_time=   Carbon::now();

            //$User->api_token=   hash('sha256', Str::random(84));

            $User->save();

    		return response()->json(['success'=>['token' => $User->api_token]]);

    	} catch (\Exception $e) {

            return response()->json(['error'=>['message' => '系统错误,联系客服!']], 500);

        }
    }


    /**
     * 忘记密码接口
     */
    public function forget(Request $request)
    {

        try{

            if($request->code !== '8888'){
                
                return response()->json(['error'=>['message' => '验证码错误']]);

            }

            \App\Buser::where('account',$request->account)->update(['password'=>md5($request->password)]);

            return response()->json(['success'=>['message' => '修改成功!', 'data'=>[]]]);

    	} catch (\Exception $e) {

            return response()->json(['error'=>['message' => '系统错误,联系客服!']]);

        }

    }
}
