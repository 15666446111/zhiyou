<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Vinkla\Hashids\Facades\Hashids;
use App\Http\Requests\RegisterRequest;

class RegisterController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function team(Request $request)
    {

        return view('register_success');

        try{

            $result = Hashids::decode($request->route('code'));

            if(empty($result)) return response()->json(['error'=>['message' => '解密失败!']]);

            return view('register');

        } catch (\Exception $e) {

            return response()->json(['error'=>['message' => '系统错误,联系客服!']]);

        }

    }


    /**
     * [team_in  会员注册 提交数据]
     * @author Pudding
     * @DateTime 2020-04-13T15:52:33+0800
     * @param    Request                  $request [description]
     * @return   [type]                            [description]
     */
    public function team_in(RegisterRequest $request)
    {
        try{

            $result = Hashids::decode($request->route('code'));

            if(empty($result)) return back()->withErrors(['参数无效!'])->withInput();

            if($request->register_password !== $request->register_confirm_password)
                return back()->withErrors(['两次密码不一致!'])->withInput();

            // 获取上级信息
            $Parent = \App\Buser::where('id', $result[0])->first();

            if(!$Parent or empty($Parent)) return back()->withErrors(['信息错误!'])->withInput();

            // 创建新用户
            $NewUser = \App\Buser::create([
                'nickname'      =>  $request->register_phone,
                'account'       =>  $request->register_phone,
                'password'      =>  md5($request->register_password),
                'phone'         =>  $request->register_phone,
                'parent'        =>  $Parent->id,
            ]);

            if(!$NewUser) return back()->withErrors(['注册失败,系统错误!'])->withInput(); 

            return view('register_success');

        } catch (\Exception $e) {

            return back()->withErrors(['注册失败,系统错误!'])->withInput(); 

        }
    }
}