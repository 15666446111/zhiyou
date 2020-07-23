<?php

namespace App\Http\Controllers\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TransferController extends Controller
{
    
    /**
     * @Author    Pudding
     * @DateTime  2020-07-23
     * @copyright [copyright]
     * @license   [license]
     * @version   [ 查询可划拨的机器列表]
     * @param     Request     $request [description]
     * @return    [type]               [description]
     */
    public function getUnBound(Request $request)
    {
        try{
            if(!$request->policy_id)  return response()->json(['error'=>['message' => '请选择政策活动!']]);

            //获取该用户该政策下未绑定未激活终端机器
            $list = \App\Merchant::select('id','merchant_terminal','merchant_sn')
                ->where('user_id',  '=', $request->user->id)
                ->where('policy_id','=', $request->policy_id)
                ->where('active_status', 0)
                ->where('bind_status', 0)->get()->toArray();
            return response()->json(['success'=>['message' => '获取成功!', 'data'=>$list]]);
        } catch (\Exception $e) {
            return response()->json(['error'=>['message' => '系统错误,联系客服!']]);
        }

    }
    

    /**
     * @Author    Pudding
     * @DateTime  2020-07-23
     * @copyright [copyright]
     * @license   [license]
     * @version   [version]
     * @param     Request     $request [description]
     * @return    [type]               [description]
     */
    public function transfer(Request $request)
    {
        try{

            if(!$request->friend_id) return response()->json(['error'=>['message' => '请选择划拨伙伴']]);

            $sonsInfo = \App\Buser::where('id', $request->friend_id)->first();
            if(empty($sonsInfo) or $sonsInfo->parent != $request->user->id){
                return response()->json(['error'=>['message' => '下级不存在或无权限！']]);
            }


            $merchants=\App\Merchant::whereIn('id', $request->id)->where('user_id', $request->user->id)
                        ->where('active_status', 0)->where('bind_status', 0)->get();

            foreach($merchants as $k=>$v){
                $v->user_id = $request->friend_id;
                $v->save();

                \App\MachineLog::create([ 'user_id'=>$request->user->id, 'friend_id'=>$request->friend_id, 'merchant_id'=>$v->id ]);
            }

            return response()->json(['success'=>['message' => '划拨成功!', 'data'=>[]]]);
        
        } catch (\Exception $e) {
            
            return response()->json(['error'=>['message' => '系统错误,联系客服!']]);

        }


    }


    /**
     * @Author    Pudding
     * @DateTime  2020-07-23
     * @copyright [copyright]
     * @license   [license]
     * @version   [ 获取回拨机器列表 ]
     * @param     Request     $request [description]
     * @return    [type]               [description]
     */
    public function backList(Request $request)
    {

        try{
            $list = \App\MachineLog::where('user_id',$request->user->id)->where('friend_id',$request->friend_id)->where('is_back', 0)
                        ->pluck('merchant_id')->toArray();
            
            $data = \App\Merchant::select('id','merchant_terminal','merchant_sn')->whereIn('id',$list)->where('policy_id',$request->policy_id)
                        ->where('active_status',0)
                        ->where('bind_status',0)
                        ->get()
                        ->toArray();
            return response()->json(['success'=>['message' => '获取成功!', 'data'=>$data]]);
        
        } catch (\Exception $e) {
            return response()->json(['error'=>['message' => '系统错误,联系客服!']]);
        }
    }


    /**
     * @Author    Pudding
     * @DateTime  2020-07-23
     * @copyright [copyright]
     * @license   [license]
     * @version   [ 划拨机器回拨 ]
     * @param     Request     $request [description]
     * @return    [type]               [description]
     */
    public function backTransfer(Request $request)
    {

        try{

            $sonsInfo = \App\Buser::where('id', $request->friend_id)->first();
            if(empty($sonsInfo) or $sonsInfo->parent != $request->user->id){
                return response()->json(['error'=>['message' => '下级不存在或无权限！']]);
            }

            // 先改写数据库
            $value = \App\MachineLog::where('user_id',$request->user->id)->where('friend_id',$request->friend_id)->whereIn('merchant_id',$request->merchant_id)->get();

            foreach ($value as $key => $v) {
                $v->is_back = $v->is_back ?? 1;
                $v->save();
                \App\Merchant::where('id', $v->merchant_id)->where('bind_status','0')->where('active_status','0')->update(['user_id'=>$request->user->id]);
            }
            return response()->json(['success'=>['message' => '回拨成功!', 'data'=>[]]]);

        } catch (\Exception $e) {
            return response()->json(['error'=>['message' => '系统错误,联系客服!']]);
        }

    }

    /**
     * @Author    Pudding
     * @DateTime  2020-07-23
     * @copyright [copyright]
     * @license   [license]
     * @version   [ 机具划拨回拨记录 ]
     * @param     Request     $request [description]
     * @return    [type]               [description]
     */
    public function transferLog(Request $request)
    {
        try{

            if(!$request->type){
                $request->type = 'my_transfer';
                // my_back 我的回拨
                // parent_transfer 上级划拨
                // parent_bank 上级回拨
            }

            $data = \App\MachineLog::where('id', '>=', 1);
            // 我的划拨
            if($request->type == 'my_transfer'){
                $data->where('user_id', $request->user->id);
            }

            // 我的回拨动
            if($request->type == 'my_back'){
                $data->where('user_id', $request->user->id)->where('is_back', 1);
            }

            // 上级划拨
            if($request->type == 'parent_transfer'){
                $data->where('friend_id', $request->user->id);
            }

            // 上级回拨
            if($request->type == 'parent_bank'){
                $data->where('friend_id', $request->user->id)->where('is_back', 1);
            }

            $list = $data->get();

            $arrs = array();

            foreach ($list as $key => $value) {
                $arrs[] = array(
                    'nickname'      =>  $value->user_a->nickname,
                    'friend_name'   =>  $value->user_b->nickname,
                    'merchant_sn'   =>  $value->merchants->merchant_sn,
                    'created_at'    =>  ($request->type == 'my_back' or $request->type == 'parent_bank') ? $value->updated_at->toDateTimeString() : $value->created_at->toDateTimeString()
                );
            }

            return response()->json(['success'=>['message' => '获取成功!', 'data'=>$arrs]]);
        
        } catch (\Exception $e) {
            
            return response()->json(['error'=>['message' => '系统错误,联系客服!']]);

        }
    }
}
