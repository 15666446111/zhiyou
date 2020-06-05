<?php

namespace App\Http\Controllers\V1;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TradeController extends Controller
{
    

	/**
	 * @Author    Pudding
	 * @DateTime  2020-06-05
	 * @copyright [copyright]
	 * @license   [license]
	 * @version   [ 获取交易详情 ]
	 * @param     Request     $request [description]
	 * @return    [type]               [description]
	 */
    public function getDetail(Request $request)
    {
    	// 按日  按月  day 按照天。 month 按月
    	// 日期。月份参数
    	// 本人  团队  传过来的参数为 current 本人 或者 team  团队
    	$current 	= $request->current ?? 'current';

    	$dataType   = $request->data_type ?? 'day';

    	if($dataType == 'day'){
    		$date  		= $request->date ?? Carbon::today()->toDateTimeString();
    	}else
    		$date       = $request->date ?? Carbon::today()->toDateTimeString();



    	$server = new \App\Http\Controllers\V1\ServerController($dataType, $date, $current, $request->user);

    	$data   = $server->getInfo();

    	dd($data);
    	
    }
}
