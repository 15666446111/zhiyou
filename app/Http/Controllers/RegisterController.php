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

        try{

            $result = Hashids::decode($request->route('code'));

            if(empty($result)) return response()->json(['error'=>['message' => '解密失败!']]);

            return view('register');

        } catch (\Exception $e) {

            return response()->json(['error'=>['message' => '系统错误,联系客服!']]);

        }

    }


    /**
     * Show the application dashboard.  扩展普通用户
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function extendUser(Request $request)
    {

        try{

            $result = Hashids::decode($request->route('code'));

            if(empty($result)) return response()->json(['error'=>['message' => '解密失败!']]);

            return view('register_user');

        } catch (\Exception $e) {

            return response()->json(['error'=>['message' => '系统错误,联系客服!']]);

        }

    }


    /**
     * Show the application dashboard.  扫码 填写表单 申请机器
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function extendTemail(Request $request)
    {

        try{

            $result = Hashids::decode($request->route('code'));

            if(empty($result)) return response()->json(['error'=>['message' => '解密失败!']]);

            return view('register_temail');

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
                'group'         =>  2,
            ]);

            if(!$NewUser) return back()->withErrors(['注册失败,系统错误!'])->withInput(); 

            return view('register_success');

        } catch (\Exception $e) {

            return back()->withErrors(['注册失败,系统错误!'])->withInput(); 

        }
    }



    /**
     * [team_in  会员注册 扩展普通用户 提交数据]
     * @author Pudding
     * @DateTime 2020-04-13T15:52:33+0800
     * @param    Request                  $request [description]
     * @return   [type]                            [description]
     */
    public function extendUserIn(RegisterRequest $request)
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
                'group'         =>  1,
            ]);

            if(!$NewUser) return back()->withErrors(['注册失败,系统错误!'])->withInput(); 

            return view('register_success');

        } catch (\Exception $e) {

            return back()->withErrors(['注册失败,系统错误!'])->withInput(); 

        }
    }



    /**
     * [extendTemailIn  表单申请pos机器 提交页面]
     * @author Pudding
     * @DateTime 2020-04-13T15:52:33+0800
     * @param    Request                  $request [description]
     * @return   [type]                            [description]
     */
    public function extendTemailIn(Request $request)
    {
        try{

            if(!$request->register_name or !$request->register_phone or !$request->register_address)
                return back()->withErrors(['请填写资料!'])->withInput();

            $result = Hashids::decode($request->route('code'));

            if(empty($result)) return back()->withErrors(['参数无效!'])->withInput();

            // 获取会员信息信息
            $user = \App\Buser::where('id', $result[0])->first();

            if(!$user or empty($user)) return back()->withErrors(['信息错误!'])->withInput();

            $agent = $user->id;

            if($user->group != 2){
                $agent = \App\Buser::getFirstVipParent($user->id);
            }

            // 创建请求
            $NewPost = \App\ApplicationForm::create([
                'name'      =>  $request->register_name,
                'phone'     =>  $request->register_phone,
                'address'   =>  $request->register_address,
                'user_id'   =>  $user->id,
                'agent_id'  =>  $agent,
            ]);

            if(!$NewPost) return back()->withErrors(['申请失败,系统错误!'])->withInput(); 

            return view('application_success');

        } catch (\Exception $e) {

            return back()->withErrors(['申请失败,系统错误!'])->withInput(); 

        }
    }
}
