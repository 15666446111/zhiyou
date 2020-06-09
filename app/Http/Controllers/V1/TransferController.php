<?php

namespace App\Http\Controllers\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TransferController extends Controller
{
    
    /**
     * 查询用户未绑定终端机器
     */
    public function getUnBound(Request $request)
    {

        try{
            if(!$request->policy_id)  
                return response()->json(['error'=>['message' => '请选择政策活动!']]);

            //获取该用户该政策下未绑定未激活终端机器
            $list = \App\Merchant::select('id','merchant_terminal','merchant_sn')

            ->where('user_id',  '=', $request->user->id)
            ->where('policy_id','=', $request->policy_id)
            ->where('active_status','!=', 1)
            ->where('bind_status','!=', 1)
            ->get()
            ->toArray();

            return response()->json(['success'=>['message' => '获取成功!', 'data'=>$list]]);
        
        } catch (\Exception $e) {
            
            return response()->json(['error'=>['message' => '系统错误,联系客服!']]);

        }

    }


    /**
     * 划拨
     */
    public function transfer(Request $request)
    {

        try{
            $merchants=\App\Merchant::whereIn('id',$request->id)->get();

            foreach($merchants as $k=>$v){

                if($v->active_status == 1 || $v->bind_status == 1){
                    return response()->json(['error'=>['message' => '请选择未绑定并且未激活的终端']]);
                }

                \App\Merchant::where('id',$v->id)->where('user_id',$request->user->id)->update(['user_id'=>$request->friend_id]);

            }

            foreach($request->id as $k=>$v){
                \App\MachineLog::create([
                    'user_id'=>$request->user->id,
                    'friend_id'=>$request->friend_id,
                    'merchant_id'=>$v
                ]);
            }

            return response()->json(['success'=>['message' => '划拨成功!', 'data'=>[]]]);
        
        } catch (\Exception $e) {
            
            return response()->json(['error'=>['message' => '系统错误,联系客服!']]);

        }


    }


    /**
     * 回拨机器列表
     */
    public function backList(Request $request)
    {

        try{

            $list = \App\MachineLog::where('user_id',$request->user->id)
            ->where('friend_id',$request->friend_id)
            ->where('is_back',0)
            ->pluck('merchant_id')
            ->toArray();
            
            $data = \App\Merchant::select('id','merchant_terminal','merchant_sn')
            ->whereIn('id',$list)
            ->where('policy_id',$request->policy_id)
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
     * 回拨
     */
    public function backTransfer(Request $request)
    {

        try{

            $res=\App\Merchant::whereIn('user_id',$request->friend_id)
            ->whereIn('id',$request->merchant_id)
            ->update(['user_id'=>$request->user->id]);

            if($res){

                $data=\App\MachineLog::where('user_id',$request->user->id)
                ->whereIn('friend_id',$request->friend_id)
                ->whereIn('merchant_id',$request->merchant_id)
                ->update(['is_back'=>1]);

            }
            
            if($data){

                return response()->json(['success'=>['message' => '回拨成功!', 'data'=>[]]]);

            }
        } catch (\Exception $e) {
            
            return response()->json(['error'=>['message' => '系统错误,联系客服!']]);

        }

    }

    /**
     * 划拨回拨记录
     */
    public function transferLog(Request $request)
    {
        try{

            $data=\App\MachineLog::select('nickname','friend_id','merchant_sn','is_back','merchants_transfer_log.created_at')
            ->join('busers','busers.id','=','merchants_transfer_log.user_id')
            ->join('merchants','merchants_transfer_log.merchant_id','=','merchants.id')
            ->where('merchants_transfer_log.user_id',$request->user->id)
            ->orderBy('merchants_transfer_log.created_at','desc')
            ->get()
            ->toArray();

            $friends_id=[];

            foreach($data as $k=>$v){

                $friends_id[]=$v['friend_id'];
     
            }
            
            $title = \App\Buser::select('id','nickname')->whereIn('id',$friends_id)->get()->toArray();

            // dd($title);
            foreach($title as $key=>$value){

                foreach($data as $i=>$p){

                    if($data[$i]['friend_id'] == $value['id']){

                        $data[$i]['friend_name']= $value['nickname'];
    
                    }
                }

            }
            
            return response()->json(['success'=>['message' => '获取成功!', 'data'=>$data]]);
        
        } catch (\Exception $e) {
            
            return response()->json(['error'=>['message' => '系统错误,联系客服!']]);

        }
    }
}
