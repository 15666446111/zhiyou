<?php

namespace App\Http\Controllers\V1;

use DB;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CashsController extends Controller
{
    /**
     * 收益页面接口
     * 
     */
    public function cashsIndex(Request $request)
    {
        try{ 

            $type = $request->type ?? 'all';

            //总收益
            $countMoney = \App\Cash::where('user_id',$request->user->id)->sum('cash_money');
            $data['revenueAll'] = number_format($countMoney / 100, 2, '.', ',');

            //今日收益
            $countToday = \App\Cash::where('user_id',$request->user->id)->whereDate('created_at', Carbon::today())->sum('cash_money');
            $data['revenueDay'] = number_format($countToday / 100, 2, '.', ',');
            
            //本月收益
            $countMonth = \App\Cash::where('user_id',$request->user->id)->whereMonth('created_at', Carbon::now()->month)->whereYear('created_at', Carbon::now()->year)->sum('cash_money');
            $data['revenueMonth'] = number_format($countMonth / 100, 2, '.', ',');

            //
            $list = \App\Cash::where('user_id', $request->user->id);

            // 收益类型
            if($type == 'cash'){
                $list->whereIn('cash_type', ['1', '2', '3', '4']);
            }

            if($type == 'return'){
                $list->whereIn('cash_type', ['5', '6', '7', '8']);
            }

            if($type == 'other'){
                $list->whereIn('cash_type', ['10', '11']);
            }        
            
            $list = $list->groupBy('date')->orderBy('date', 'desc')->get(
                        array(
                            DB::raw('Date(created_at) as date'),
                            DB::raw('SUM(cash_money) as money')
                        )
                    );

            $weekarray=array("日","一","二","三","四","五","六");

            foreach ($list as $key => $value) {

                $dt = Carbon::parse($value->date);

                // 循环每一天的数据查询
                $listdata = \App\Cash::where('user_id', $request->user->id)->with('trades')->whereDate('created_at', $value->date);

                if($type == 'cash'){
                    $listdata->whereIn('cash_type', ['1', '2', '3', '4']);
                }

                if($type == 'return'){
                    $listdata->whereIn('cash_type', ['5', '6', '7', '8']);
                }

                if($type == 'other'){
                    $listdata->whereIn('cash_type', ['10', '11']);
                }   

                $listdata = $listdata->orderBy('created_at', 'desc')->get();
                
                $arrs = [];

                foreach ($listdata as $k => $v) {
                    $arrs[] = [
                        'type'  => $v->cash_type, 
                        'money' => number_format($v->cash_money / 100, 2, '.', ','), 
                        'sn'    => $v->trades->merchant_sn, 
                        'orderMoney' => number_format($v->trades->money / 100, 2, '.', ','),
                        'date'  => $v->created_at->toDateTimeString(),
                    ];
                }
                
                $data['cash'][] = array(
                    'title' => $dt->year."年".$dt->month."月".$dt->day."日", 
                    'money' => number_format($value->money / 100 , 2, '.', ','), 
                    'week'  => "星期".$weekarray[$dt->dayOfWeek],
                    'list'  => $arrs,
                );
            }
            
            return response()->json(['success'=>['message' => '获取成功!', 'data' => $data]]); 

    	} catch (\Exception $e) {
            
            return response()->json(['error'=>['message' => '系统错误,联系客服!']]);

        }
    }
}
