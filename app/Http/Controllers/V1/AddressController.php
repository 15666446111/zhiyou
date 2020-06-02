<?php

namespace App\Http\Controllers\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AddressController extends Controller
{
    /**
     * 添加用户收货地址接口
     */
    public function address(Request $request)
    {
        try{ 
            
            $data=\App\Address::create([
                'user_id'=>$request->user->id,
                'name'=>$request->name,
                'tel'=>$request->tel,
                'province'=>$request->province,
                'city'=>$request->city,
                'area'=>$request->area,
                'detail'=>$request->detail,
                'is_default'=>'0',  
            ]); 
            if($data){

                return response()->json(['success'=>['message' => '添加成功!', 'data' => []]]); 

            }

    	} catch (\Exception $e) {
            
            return response()->json(['error'=>['message' => '系统错误,联系客服!']]);

        }
    }

    /**
     * 查询用户收货地址接口
     */
    public function getAAddress(Request $request)
    {
        try{ 
            
            $data=\App\Address::where('user_id',$request->user->id)->get();
            
            return response()->json(['success'=>['message' => '获取成功!', 'data' => $data]]); 

    	} catch (\Exception $e) {
            
            return response()->json(['error'=>['message' => '系统错误,联系客服!']]);

        }
    }

    /**
     * 删除用户收货地址接口
     */
    public function deleteAddress(Request $request)
    {
        try{ 
            
            \App\Address::where('user_id',$request->user->id)->delete();
            
            return response()->json(['success'=>['message' => '删除成功!', 'data' => []]]); 

    	} catch (\Exception $e) {
            
            return response()->json(['error'=>['message' => '系统错误,联系客服!']]);

        }
    }

    /**
     * 修改用户收货地址接口
     */
    public function updateAddress(Request $request)
    {
        try{ 
            
            $data=\App\Address::where('user_id',$request->user->id)->update([ 
                'name'=>$request->name,
                'tel'=>$request->tel,
                'province'=>$request->province,
                'city'=>$request->city,
                'area'=>$request->area,
                'detail'=>$request->detail,
                'is_default'=>$request->is_default,
            ]);
            if($data){

                return response()->json(['success'=>['message' => '修改成功!', 'data' => []]]); 

            }

    	} catch (\Exception $e) {
            
            return response()->json(['error'=>['message' => '系统错误,联系客服!']]);

        }
    }
}
