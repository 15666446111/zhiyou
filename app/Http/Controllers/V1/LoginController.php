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

            $User->api_token=   hash('sha256', Str::random(84));

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
            if($request->password !== $request->password1){
                return response()->json(['error'=>['message' => '请保持密码一致']]);
            }

            if(!$this->verifyCode($request->phone, $request->code)){
                return response()->json(['error'=>['message' => '验证码不正确或已过期']]);
            }

            \App\Buser::where('account',$request->account)->update(['password'=>md5($request->password)]);

            return response()->json(['success'=>['message' => '修改成功!', 'data'=>[]]]);

    	} catch (\Exception $e) {

            return response()->json(['error'=>['message' => '系统错误,联系客服!']]);

        }

    }

    /**
     * @Author    Pudding
     * @DateTime  2020-07-08
     * @copyright [copyright]
     * @license   [license]
     * @version   [注册发送验证码]
     * @param     Request     $request [description]
     * @return    [type]               [description]
     */
    public function code(Request $request)
    {
        try{

            if(!$request->phone) return response()->json(['error'=>['message' => '手机号不存在!']]);
            // 发送验证码
            $appliction = new \App\Services\Sms\SendSmsController;

            $res = $appliction->send($request->phone, rand(1000,9999));

            if($res['code'] = 10000){
                return response()->json(['success'=>['message' => '发送成功!']]);
            }else{
                return response()->json(['error'=>['message' => $res['message']]]);
            }
            
        } catch (\Exception $e) {

            return response()->json(['error'=>['message' => '发送失败!']]);

        }
    }

    /**
     * @Author    Pudding
     * @DateTime  2020-07-09
     * @copyright [copyright]
     * @license   [license]
     * @version   [ 验证验证码是否正确 ]
     * @param     [type]      $phone [description]
     * @param     [type]      $code  [description]
     * @return    [type]             [description]
     */
    public function verifyCode($phone, $code)
    {
        try{
            // 获取到该用户的最后一条可用的验证码
            $codeMsg = \App\Sms::where('phone', $phone)
                                    ->where('is_use', 0)
                                    ->where('out_time', '>=', Carbon::now()->toDateTimeString())
                                    ->orderBy('id', 'desc')->first();

            if(empty($codeMsg) or !$codeMsg){
                \App\Sms::where('phone', $phone)->update(['is_use' => 1]);
                return false;
            }

            if($codeMsg->code != $code){
                return false;
            }

            \App\Sms::where('phone', $phone)->update(['is_use' => 1]);

            return true;
            
        } catch (\Exception $e) {
            return false;
        }
    }
}
