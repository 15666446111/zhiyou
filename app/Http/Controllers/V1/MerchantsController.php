<?php

namespace App\Http\Controllers\V1;
use DB;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MerchantsController extends Controller
{
    
    /**
     * 首页商户登记绑定接口
     */
    public function registers(Request $request)
    {
        try{ 
             
            \App\Merchant::where('user_id',$request->user->id)->where('merchant_sn',$request->merchant_sn)->update([
                'merchant_name'     => $request->merchant_name,
                'user_phone'        => $request->merchant_phone,
                'bind_status'       => 1,
                'bind_time'         => Carbon::now()->toDateTimeString(),
            ]);
            
            return response()->json(['success'=>['message' => '登记成功!', []]]); 

    	} catch (\Exception $e) {
            
            return response()->json(['error'=>['message' => '系统错误,联系客服!']]);

        }
    }


    /**
     * @Author    Pudding
     * @DateTime  2020-07-22
     * @copyright [copyright]
     * @license   [license]
     * @version   [ 首页 - 商户管理列表 ]
     * @param     Request     $request [description]
     * @return    [type]               [description]
     */
    public function merchantsList(Request $request)
    {
        try{ 

            $arrs;
            
            $bind = \App\Merchant::where('user_id', $request->user->id)->where('bind_status', '1')->get();


            foreach ($bind as $key => $value) {
                $arrs['Bound'][] = array(
                    'id'                =>  $value->id,
                    'merchant_name'     =>  $value->merchant_name,
                    'merchant_number'   =>  $value->merchant_number,
                    'merchant_terminal' =>  $value->merchant_terminal,
                    'merchant_sn'       =>  $value->merchant_sn,
                    'money'             =>  $value->tradess_sn->sum('money'),
                    'created_at'        =>  $value->created_at,
                    'bind_time'         =>  $value->bind_time,
                    'active_time'       =>  $value->active_time,
                    'time'              =>  $value->bind_time ?? $value->active_time
                );
            }
            
            
            $UnBind =\App\Merchant::where('user_id', $request->user->id)->where('bind_status', '0')->get();
            
            foreach ($UnBind as $key => $value) {
                $arrs['UnBound'][] = array(
                    'id'                =>  $value->id,
                    'merchant_name'     =>  $value->merchant_name,
                    'merchant_number'   =>  $value->merchant_number,
                    'merchant_terminal' =>  $value->merchant_terminal,
                    'merchant_sn'       =>  $value->merchant_sn,
                    'money'             =>  $value->tradess_sn->sum('money'),
                    'created_at'        =>  $value->created_at,
                    'bind_time'         =>  $value->bind_time,
                    'active_time'       =>  $value->active_time,
                    'time'              =>  $value->bind_time ?? $value->active_time
                );
            }
            
            return response()->json(['success'=>['message' => '获取成功!', 'data' => $arrs]]); 

    	} catch (\Exception $e) {
            
            return response()->json(['error'=>['message' => '系统错误，请联系客服']]);

        }
    }


    /**
     * @Author    Pudding
     * @DateTime  2020-07-22
     * @copyright [copyright]
     * @license   [license]
     * @version   [ 首页 - 商户管理 - 商户详情 ]
     * @param     Request     $request [description]
     * @return    [type]               [description]
     */
    public function merchantInfo(Request $request)
    {
        try{ 
             
            $data=\App\Merchant::where('user_id',$request->user->id)->where('id',$request->id)->first();
            
            $data['time'] = $data->bind_time ? $data->bind_time : $data->active_time;

            return response()->json(['success'=>['message' => '获取成功!', 'data' => $data]]);   

    	} catch (\Exception $e) {
            
            return response()->json(['error'=>['message' => '系统错误，请联系客服']]);

        }
    }


    /**
     * @Author    Pudding
     * @DateTime  2020-07-22
     * @copyright [copyright]
     * @license   [license]
     * @version   [ 首页 - 商户管理 - 商户详情 - 交易数据 ]
     * @param     Request     $request [description]
     */
    public function MerchantDetails(Request $request)
    {

        try{ 

            if(!$request->merchant) return response()->json(['error'=>['message' => 'sn号无效']]);

            switch ($request->data_type) {
                case 'month':
                    $StartTime = Carbon::now()->startOfMonth()->toDateTimeString();
                    break;
                case 'day':
                    $StartTime = Carbon::today()->toDateTimeString();
                    break;
                case 'count':
                    $StartTime = Carbon::createFromFormat('Y-m-d H', '1970-01-01 00')->toDateTimeString();
                    break;
                default:
                    $StartTime = Carbon::today()->toDateTimeString();
                    break;
            }

            $EndTime = Carbon::now()->toDateTimeString();

            $data = \App\Trade::select('card_type','card_number','trade_type', DB::raw('format(money / 100, 2) as money'), 'trade_time', 'trade_status', 'merchant_sn', 'merchant_id')
                    ->where('merchant_sn', $request->merchant)->whereBetween('trade_time', [ $StartTime,  $EndTime])->get();

            return response()->json(['success'=>['message' => '获取成功!', 'data'=>$data]]);
        
        } catch (\Exception $e) {
                
            return response()->json(['error'=>['message' => '系统错误，请联系客服']]);

        }
    }



}
