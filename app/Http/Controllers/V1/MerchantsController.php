<?php

namespace App\Http\Controllers\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MerchantsController extends Controller
{
    /**
     * 商户首页管理
     */
    public function merchantsIndex(Request $request)
    {
        try{ 
             
            $limit = 15; 

            $page  = $request->page ? $request->page - 1 : 0;

            if(!is_numeric($page)){
                return response()->json(['error'=>['message' => '参数错误!']]); 
            }

            $page   = $page < 0 ? 0 : $page ;

            $page   = $page * $limit;

            $data=\App\Merchant::where('bind_status',$request->bind_status)->orderBy('id', 'desc')
            ->offset($page)->limit($limit)->get(); 
            
            return response()->json(['success'=>['message' => '获取成功!', 'data' => $data]]); 

    	} catch (\Exception $e) {
            
            return response()->json(['error'=>['message' => '系统错误,联系客服!']]);

        }
    }



    /**
     * 首页商户登记绑定接口
     */
    public function registers(Request $request)
    {
        try{ 
             
            \App\Merchant::where('user_id',$request->user->id)->where('merchant_terminal',$request->merchant_terminal)->update([
                'merchant_name'=>$request->merchant_name,
                'user_phone'=>$request->merchant_phone,
                'bind_status'=>1
            ]);
            
            return response()->json(['success'=>['message' => '登记成功!', []]]); 

    	} catch (\Exception $e) {
            
            return response()->json(['error'=>['message' => '系统错误,联系客服!']]);

        }
    }

    /**
     * 商户列表接口
     */
    public function merchantsList(Request $request)
    {
        try{ 
            $limit = 15; 

            $page  = $request->page ? $request->page - 1 : 0;

            if(!is_numeric($page)){
                return response()->json(['error'=>['message' => '参数错误!']]); 
            }

            $page   = $page < 0 ? 0 : $page ;

            $page   = $page * $limit;
             
            $data['Bound']=\App\Merchant::select('merchant_name','merchant_number','merchant_sn')
                            ->where(['user_id'=>$request->user->id,'bind_status'=>1]) 
                            ->orderBy('id', 'desc')
                            ->offset($page)
                            ->limit($limit)
                            ->get()
                            ->toArray();

            foreach($data['Bound'] as $k=>$v){
                $data['Bound'][$k]['create_time']='2020-06-02 11:39:01';
                $data['Bound'][$k]['amount']='80000'; 
            }


            $data['Unbound']=\App\Merchant::select('merchant_name','merchant_number','merchant_sn')
                            ->where(['user_id'=>$request->user->id,'bind_status'=>0]) 
                            ->orderBy('id', 'desc')
                            ->offset($page)
                            ->limit($limit)
                            ->get()
                            ->toArray();

            foreach($data['Unbound'] as $key=>$value){
                $data['Unbound'][$key]['create_time']='2020-06-02 11:39:01';
                $data['Unbound'][$key]['amount']='80000'; 
            }
            
            return response()->json(['success'=>['message' => '获取成功!', 'data' => $data]]); 

    	} catch (\Exception $e) {
            
            return response()->json(['error'=>['message' => '系统错误,联系客服!']]);

        }
    }

    /**
     * 个人商户详情接口
     */
    public function merchantInfo(Request $request)
    {
        try{ 
             
            $data=\App\Merchant::where('user_id',$request->user->id)
            ->where('merchant_name',$request->merchant_name)
            ->where('user_phone',$request->user_phone)
            ->get();
            
            return response()->json(['success'=>['message' => '获取成功!', 'data' => $data]]);   

    	} catch (\Exception $e) {
            
            return response()->json(['error'=>['message' => '系统错误,联系客服!']]);

        }
    }
}
