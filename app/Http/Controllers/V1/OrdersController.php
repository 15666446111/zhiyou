<?php

namespace App\Http\Controllers\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrdersController extends Controller
{
    /**
     * 生成订单接口
     */
    public function orderCreate(Request $request)
    {

        try{ 
            
            //生成订单编号
            $order_no=date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
            
            \App\Order::where('user_id',$request->user->id)->create([
                'order_no'=>$order_no,
                'user_id'=>$request->user->id,
                'product_id'=>$request->product_id,
                'product_price'=>$request->product_price,
                'numbers'=>$request->numbers,
                'price'=>$request->price,
                'address'=>$request->address,
                'status'=>0,
                'remark'=>$request->remark
            ]);

            return response()->json(['success'=>['message' => '生成订单成功!', 'data' => []]]); 


    	} catch (\Exception $e) {
            
            return response()->json(['error'=>['message' => '系统错误,联系客服!']]);

        }

    }


    /**
     * 查询订单接口
     */
    public function  getOrder(Request $request)
    {

        try{ 
            
            $data=\App\Order::join('products','orders.product_id','=','products.id')->where('user_id',$request->user->id)->get();

            return response()->json(['success'=>['message' => '获取成功!', 'data' => $data]]); 


    	} catch (\Exception $e) {
            
            return response()->json(['error'=>['message' => $e->getMessage()]]);

        }

    }
}