<?php

namespace App\Http\Controllers\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BonusController extends Controller
{
    

	/**
	 * @Author    Pudding
	 * @DateTime  2020-07-18
	 * @copyright [copyright]
	 * @license   [license]
	 * @version   [ 用户在首页看到的分红奖励页面数据 ]
	 * @param     Request     $request [description]
	 * @return    [type]               [description]
	 */
    public function page(Request $request)
    {
    	//try{ 

    		$data = array('SelfFirst' => 0, 'SlefSecond' => 0, 'SlefThree' => 0, 'TeamFirst' => 0, 'TeamSecond' => 0, "Team_Three" => 0);

    		// 本人达标获得的总奖励
    		$Count = \App\Cash::where('user_id', $request->user->id)->whereIn('cash_type', ['9', '10'])->orderBy('created_at', 'desc')->get();
    		$data['CountSelf'] = number_format( $Count->sum('cash_money') / 100, 2, '.', ',');

    		// 循环本人的达标返现记录
    		foreach ($Count as $key => $value) {

    			$data['list'][] = array(
    				'name' 	=> $value->trades->merchant_name, 
    				'money' => number_format($value->cash_money / 100, 2, '.', ','),
    				'time'	=> $value->created_at->toDateTimeString(),
    				'sn'	=> $value->trades->merchant_sn
    			);


    			if($value->cash_type == "9"){
    				if($value->trades->standed == "1"){
    					$data['SelfFirst'] += $value->cash_money;
    				}

    				if($value->trades->standed == "2"){
    					$data['SlefSecond'] += $value->cash_money;
    				}
    				
    				if($value->trades->standed == "3"){
    					$data['SlefThree'] += $value->cash_money;
    				}
    			}

    			if($value->cash_type == "10"){
    				if($value->trades->standed == "1"){
    					$data['TeamFirst'] += $value->cash_money;
    				}

    				if($value->trades->standed == "2"){
    					$data['TeamSecond'] += $value->cash_money;
    				}
    				
    				if($value->trades->standed == "3"){
    					$data['Team_Three'] += $value->cash_money;
    				}
    			}

    			# code...
    		}

    		return json_encode(['success'=>['message'=> '获取成功!', 'data' => $data]]);


/*    	} catch (\Exception $e) {
            
            return response()->json(['error'=>['message' => '系统错误,联系客服!']]);

        }*/
    }
}
