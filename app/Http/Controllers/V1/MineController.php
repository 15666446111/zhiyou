<?php

namespace App\Http\Controllers\V1;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class MineController extends Controller
{	
	/**
	 * @version  [<我的栏位 获取个人信息>]
	 * @author   Pudding   
	 * @DateTime 2020-04-08T17:17:40+0800
	 * @param    Request
	 * @return   [type]
	 */
    public function info(Request $request)
    {
    	try{

            return response()->json(['success'=>['message' => '获取成功!', 'data' => [
                'id'        =>  $request->user->id,
                'headimg'   =>  $request->user->headimg,
                'nickname'  =>  $request->user->nickname,
                'username'  =>  $request->user->account,
                'blance'    =>  $request->user->wallets->cash_blance+$request->user->wallets->return_blance,
                'group'     =>  $request->user->groups->name,
                'group_id'  =>  $request->user->group,
                'cash_blance'   =>  $request->user->wallets->cash_blance,
                'return_blance' =>  $request->user->wallets->return_blance,
            ]]]);

    	} catch (\Exception $e) {
            
            return response()->json(['error'=>['message' => '系统错误,联系客服!']]);

        }
    }


    /**
     * @Author    Pudding
     * @DateTime  2020-05-16
     * @copyright [copyright]
     * @license   [license]
     * @version   [获取伙伴用户的详细]
     * @param     Request user id 伙伴的id
     * @return    [type]
     */
    public function userInfo(Request $request)
    {
        try{


            //return response()->json(['success'=>['message' => '获取成功!', 'data' => $request->team_user]]);

            if (!$request->team_user) {
                return response()->json(['success'=>['message' => '获取成功!', 'data' => []]]);
            }

            // 获取用户信息
            $user = \App\Buser::where('id', $request->team_user)->first();

            return response()->json(['success'=>['message' => '获取成功!', 'data' => $user]]);

        } catch (\Exception $e) {
            
            return response()->json(['error'=>['message' => '系统错误,联系客服!']]);

        }

    }


    /**
     * [draw_log  APP 获取提现信息]
     * @author Pudding
     * @DateTime 2020-04-13T08:53:57+0800
     * @param    Request                  $request [description]
     * @return   [type]                            [description]
     */
    public function draw_log(Request $request)
    {
        try{

            $limit = 15; 

            $page  = $request->page ? $request->page - 1 : 0;

            if(!is_numeric($page)){
                return response()->json(['error'=>['message' => '参数错误!']]); 
            }

            $page   = $page < 0 ? 0 : $page ;

            $page   = $page * $limit;

            $data   = \App\Withdraw::where('user_id', $request->user->id)->orderBy('id', 'desc')
                        ->offset($page)->limit($limit)->get();
                        
            return response()->json(['success'=>['message' => '获取成功!', 'data' => $data]]);

        } catch (\Exception $e) {
            
            return response()->json(['error'=>['message' => '系统错误,联系客服!']]);

        }
    }
}
