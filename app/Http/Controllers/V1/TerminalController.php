<?php

namespace App\Http\Controllers\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TerminalController extends Controller
{
	/**
	 * @Author    Pudding
	 * @DateTime  2020-06-07
	 * @copyright [copyright]
	 * @license   [license]
	 * @version   [获取机器的活动详情]
	 * @param     Request     $request [description]
	 * @return    [type]               [description]
	 */
    public function getActiveDetail(Request $request)
    {	
    	/**
    	 * @var 返回该机器的活动情况
    	 */
    	if(!$request->terminal ) return response()->json(['error'=>['message' => '缺少机器终端']]);

    	$ActiveInfo = array();

    	$countTradeMoney =  \App\Trade::where('terminal', $request->terminal )->sum('money');
    	
    	// 获得该机器总交易额
    	$ActiveInfo['countTradeMoney'] =number_format($countTradeMoney / 100, 2, '.', ',');


    	$ActiveInfo['tips'] = "达标统计的是T0、T1、云闪付贷记卡交易之和，非总交易额";
    	

    	return response()->json(['success'=>['message' => '获取成功', 'data' =>$ActiveInfo ]]);

    }
}
