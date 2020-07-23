<?php

namespace App\Http\Controllers\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BankController extends Controller
{

    /**
     * @Author    Pudding
     * @DateTime  2020-07-22
     * @copyright [copyright]
     * @license   [license]
     * @version   [ 首页 - 我的 - 新增结算卡]
     * @param     Request     $request [description]
     * @return    [type]               [description]
     */
    public function insertBank(Request $request)
    {
        try{ 

            if($request->is_default == 1) \App\Bank::where('user_id',$request->user->id)->update(['is_default'=>0]);

            \App\Bank::create([
                'user_id'=>$request->user->id,
                'name'=>$request->name,
                'bank_name'=>$request->bank_name,
                'bank'=>$request->bank,
                'number'=>$request->number,
                'open_bank'=>$request->open_bank,
                'is_del'=>0,
                'is_default'=>$request->is_default
            ]);
            return response()->json(['success'=>['message' => '添加成功!', []]]); 

    	} catch (\Exception $e) {
            return response()->json(['error'=>['message' => '系统错误,联系客服!']]);
        }
    }

    /**
     * @Author    Pudding
     * @DateTime  2020-07-22
     * @copyright [copyright]
     * @license   [license]
     * @version   [ 首页 - 我的 - 查询结算卡]
     * @param     Request     $request [description]
     * @return    [type]               [description]
     */
    public function selectBank(Request $request)
    {
        try{ 
            
            $data=\App\Bank::where('user_id',$request->user->id)
            ->where('is_del',0)
            ->orderBy('is_default','desc')
            ->get();

            return response()->json(['success'=>['message' => '获取成功!', 'data'=>$data]]); 

    	} catch (\Exception $e) {
            
            return response()->json(['error'=>['message' => '系统错误,联系客服!']]);

        }
    }


    /**
     * @Author    Pudding
     * @DateTime  2020-07-22
     * @copyright [copyright]
     * @license   [license]
     * @version   [ 首页 - 我的 - 查询单个结算卡]
     * @param     Request     $request [description]
     * @return    [type]               [description]
     */
    public function bankFirst(Request $request)
    {
        try{ 
            
            $data=\App\Bank::where('user_id',$request->user->id)
                            ->where('id',$request->id)
                            ->get();

            return response()->json(['success'=>['message' => '获取成功!', 'data'=>$data]]); 

    	} catch (\Exception $e) {
            
            return response()->json(['error'=>['message' => '系统错误,联系客服!']]);

        }
    }

    /**
     * @Author    Pudding
     * @DateTime  2020-07-22
     * @copyright [copyright]
     * @license   [license]
     * @version   [ 首页 - 我的 - 查询默认结算卡]
     * @param     Request     $request [description]
     * @return    [type]               [description]
     */
    public function bankDefault(Request $request)
    {
        try{ 
            
            $data=\App\Bank::where('user_id',$request->user->id)
                            ->where('is_default','1')
                            ->where('is_del',0)
                            ->first();

            return response()->json(['success'=>['message' => '获取成功!', 'data'=>$data]]); 

    	} catch (\Exception $e) {
            
            return response()->json(['error'=>['message' => '系统错误,联系客服!']]);

        }
    }


    /**
     * @Author    Pudding
     * @DateTime  2020-07-22
     * @copyright [copyright]
     * @license   [license]
     * @version   [ 首页 - 我的 - 删除结算卡]
     * @param     Request     $request [description]
     * @return    [type]               [description]
     */
    public function unsetBank(Request $request)
    {
        try{ 
            
            \App\Bank::where('user_id',$request->user->id)->where('id',$request->id)->update(['is_del'=>1]);

            return response()->json(['success'=>['message' => '删除成功!', []]]); 

    	} catch (\Exception $e) {
            
            return response()->json(['error'=>['message' => '系统错误,联系客服!']]);

        }
    }

    /**
     * @Author    Pudding
     * @DateTime  2020-07-22
     * @copyright [copyright]
     * @license   [license]
     * @version   [ 首页 - 我的 - 修改结算卡]
     * @param     Request     $request [description]
     * @return    [type]               [description]
     */
    public function updateBank(Request $request)
    {
        try{ 

            if(empty($request->is_default) || $request->is_default == 0){

                \App\Bank::where('user_id',$request->user->id)->where('id',$request->id)->update([
                    'name'=>$request->name,
                    'bank_name'=>$request->bank_name, 
                    'bank'=>$request->bank,
                    'number'=>$request->number,
                    'open_bank'=>$request->open_bank,
                    'is_default'=>0
                ]);

            }else{

                \App\Bank::where('user_id',$request->user->id)->update(['is_default'=>0]);

                \App\Bank::where('user_id',$request->user->id)->where('id',$request->id)->update([
                    'name'=>$request->name,
                    'bank_name'=>$request->bank_name, 
                    'bank'=>$request->bank,
                    'number'=>$request->number,
                    'open_bank'=>$request->open_bank,
                    'is_default'=>1
                ]);

            }
            


            return response()->json(['success'=>['message' => '修改成功!', []]]); 


    	} catch (\Exception $e) {
            
            return response()->json(['error'=>['message' => '系统错误,联系客服!']]);

        }
    }
}
