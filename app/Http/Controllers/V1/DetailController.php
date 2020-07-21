<?php

namespace App\Http\Controllers\V1;

use DB;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DetailController extends Controller
{


	/**
	 * [$type 查询的类型 查询本人还是代理的 区别 本人的显示自己与所有代理。代理的只显示代理名下]
	 * @var [type]
	 */
	protected $type;

	/**
	 * [$begin 查询的开始时间]
	 * @var [type]
	 */
	protected $begin;


	/**
	 * [$end 查询的结束日期]
	 * @var [type]
	 */
	protected $end;


	/**
	 * [$dateType 日期类型]
	 * @var [type]
	 */
	protected $dateType;


	/**
	 * [$date 查询的时间。格式为 2020-07]
	 * @var [type]
	 */
	protected $date;

    /**
     * @Author    Pudding
     * @DateTime  2020-07-20
     * @copyright [copyright]
     * @license   [license]
     * @version   [团队 - 业务详情  - 交易详情 ]
     * @param     Request     $request [description]
     * @return    [type]               [description]
     */
    public function TradeDetail(Request $request)
    {
    	try{
	    	$this->type = (!$request->type or $request->type =='self') ? 'self' : 'agent';

	    	// 如果查询的类型为agent 代表要查询直接下级的信息 所以agent_id 不能为空
	    	if($this->type == 'agent'){
	    		if(!$request->agent_id){
	    			return response()->json(['error'=>['message' => '缺少代理信息!']]);
	    		}
	    	}

	    	$this->dateType = (!$request->dateType or $request->dateType == 'day') ? 'day' : 'month';

	    	if(!$request->date){

	    		if($this->dateType == 'day'){
	    			$this->begin = Carbon::today()->toDateTimeString();
	    		}
	    		if($this->dateType == 'month'){
	    			$this->begin = Carbon::now()->firstOfMonth()->toDateTimeString();
	    		}

	    		if($this->dateType == 'day'){
	    			$this->end   = Carbon::tomorrow()->toDateTimeString();
	    		}
	    		if($this->dateType == 'month'){
	    			$this->end 	 = Carbon::now()->addMonth(1)->firstOfMonth()->toDateTimeString();
	    		}

	    	}else{

	    		$this->begin = Carbon::createFromFormat('Y-m', $request->date)->firstOfMonth()->toDateTimeString();

	    		$this->end 	 = Carbon::createFromFormat('Y-m', $request->date)->addMonth(1)->firstOfMonth()->toDateTimeString();
	    	}

	    	//dd($this->begin);

	    	$data = array();


	    	$trade_type = array('ENJOY', 'CARDPAY', 'SMALLFREEPAY', 'CLOUDPAY', 'WXQRPAY', 'ALIQRPAY', 'UNIONQRPAY');

	    	if($this->type == 'self'){
	    		$selfData = \App\Trade::whereHas('merchants_sn', function($query) use ($request){
	    		    			$query->where('user_id', $request->user->id);
	    		    		})
	    					->where('trade_time', '>=', $this->begin)->where('trade_time', '<=', $this->end)
	    					->whereIn('trade_type', $trade_type)->groupBy('trade_type')
	    					->select('trade_type', DB::raw('format(SUM(money) / 100, 2) as money'))
	    					->get()->toArray();

	    		foreach ($selfData as $key => $value) {
	    			if($value['trade_type'] == 'ENJOY') $selfData[$key]['title'] = '优享交易';
	    			if($value['trade_type'] == 'CARDPAY')$selfData[$key]['title'] = '普通交易';
	    			if($value['trade_type'] == 'SMALLFREEPAY')$selfData[$key]['title'] = '小额双免';
	    			if($value['trade_type'] == 'CLOUDPAY') $selfData[$key]['title'] = '云闪付';
	    			if($value['trade_type'] == 'WXQRPAY') $selfData[$key]['title'] = '微信扫码';
	    			if($value['trade_type'] == 'ALIQRPAY') $selfData[$key]['title'] = '支付宝扫码';
	    			if($value['trade_type'] == 'UNIONQRPAY') $selfData[$key]['title'] = '银联扫码';
	    		}
	    		$data['self'] = $selfData;

	    		// 获取所有代理的
	    		$agent = $this->getAgent($request->user->id);
	    		//dd($agent);
	    		$agentData = \App\Trade::whereHas('merchants_sn', function($query) use ($agent){
	    		    			$query->whereIn('user_id', $agent);
	    		    		})
	    					->where('trade_time', '>=', $this->begin)->where('trade_time', '<=', $this->end)
	    					->whereIn('trade_type', $trade_type)->groupBy('trade_type')
	    					->select('trade_type', DB::raw('format(SUM(money) / 100, 2) as money'))
	    					->get()->toArray();

	    		foreach ($agentData as $key => $value) {
	    			if($value['trade_type'] == 'ENJOY') $agentData[$key]['title'] = '优享交易';
	    			if($value['trade_type'] == 'CARDPAY')$agentData[$key]['title'] = '普通交易';
	    			if($value['trade_type'] == 'SMALLFREEPAY')$agentData[$key]['title'] = '小额双免';
	    			if($value['trade_type'] == 'CLOUDPAY') $agentData[$key]['title'] = '云闪付';
	    			if($value['trade_type'] == 'WXQRPAY') $agentData[$key]['title'] = '微信扫码';
	    			if($value['trade_type'] == 'ALIQRPAY') $agentData[$key]['title'] = '支付宝扫码';
	    			if($value['trade_type'] == 'UNIONQRPAY') $agentData[$key]['title'] = '银联扫码';
	    		}
	    		$data['agent'] = $agentData;

	    	}


	    	if($this->type == 'agent'){

	    		$agentInfo = \App\Buser::where('id', $request->agent_id)->first();

	    		if(!$agentInfo or empty($agentInfo) or $agentInfo->parent != $request->user->id){
	    			return response()->json(['error'=>['message' => '无此代理信息!']]);
	    		}

	    		$agent = $this->getAgent($request->agent_id);

	    		$agentData = \App\Trade::whereHas('merchants_sn', function($query) use ($agent){
	    		    			$query->whereIn('user_id', $agent);
	    		    		})
	    					->where('trade_time', '>=', $this->begin)->where('trade_time', '<=', $this->end)
	    					->whereIn('trade_type', $trade_type)->groupBy('trade_type')
	    					->select('trade_type', DB::raw('format(SUM(money) / 100, 2) as money'))
	    					->get()->toArray();

	    		foreach ($agentData as $key => $value) {
	    			if($value['trade_type'] == 'ENJOY') $agentData[$key]['title'] = '优享交易';
	    			if($value['trade_type'] == 'CARDPAY')$agentData[$key]['title'] = '普通交易';
	    			if($value['trade_type'] == 'SMALLFREEPAY')$agentData[$key]['title'] = '小额双免';
	    			if($value['trade_type'] == 'CLOUDPAY') $agentData[$key]['title'] = '云闪付';
	    			if($value['trade_type'] == 'WXQRPAY') $agentData[$key]['title'] = '微信扫码';
	    			if($value['trade_type'] == 'ALIQRPAY') $agentData[$key]['title'] = '支付宝扫码';
	    			if($value['trade_type'] == 'UNIONQRPAY') $agentData[$key]['title'] = '银联扫码';
	    		}
	    		$data['agent'] = $agentData;

	    	}

	    	return response()->json(['success'=>['message' => '信息获取成功!', 'data' => $data ]]);

    	} catch (\Exception $e) {

            return response()->json(['error'=>['message' => '系统错误,联系客服!']]);

        }
    }


    /**
     * @Author    Pudding
     * @DateTime  2020-06-06
     * @copyright [copyright]
     * @license   [license]
     * @version   [ 团队 - 业务详情 - 激活数据 ]
     * @param     Request     $request [description]
     * @return    [type]               [description]
     */
    public function AgentActive(Request $request)
    {
    	try{
	    	$this->type = (!$request->type or $request->type =='self') ? 'self' : 'agent';

	    	// 如果查询的类型为agent 代表要查询直接下级的信息 所以agent_id 不能为空
	    	if($this->type == 'agent'){
	    		if(!$request->agent_id){
	    			return response()->json(['error'=>['message' => '缺少代理信息!']]);
	    		}
	    	}

	    	$this->dateType = (!$request->dateType or $request->dateType == 'day') ? 'day' : 'month';

	    	if(!$request->date){

	    		$this->begin = $this->dateType == 'day' ? Carbon::today()->toDateTimeString() : Carbon::now()->firstOfMonth()->toDateTimeString();

	    		$this->end = $this->dateType == 'day' ? Carbon::tomorrow()->toDateTimeString() : Carbon::now()->addMonth(1)->firstOfMonth()->toDateTimeString();
	    	}else{

	    		$this->begin = Carbon::createFromFormat('Y-m', $request->date)->firstOfMonth()->toDateTimeString();

	    		$this->end 	 = Carbon::createFromFormat('Y-m', $request->date)->addMonth(1)->firstOfMonth()->toDateTimeString();
	    	}

	    	$data = array();

	    	if($this->type == 'self'){
	    		$selfData = \App\Policy::withCount(['merchants' => function($query) use ($request) {
	    			$query->where('user_id', $request->user->id)->where('active_status', 1)->where('active_time', '>=', $this->begin)->where('active_time', '<=', $this->end);
	    		}])->get();
	    		
	    		foreach ($selfData as $key => $value) {
	    			$data['self'][]	=  array('title' => $value->title, 'count' => $value->merchants_count);
	    		}

	    		// 获取所有代理的
	    		$agent = $this->getAgent($request->user->id);
	    		$agentData = \App\Policy::withCount(['merchants' => function($query) use ($agent) {
	    			$query->whereIn('user_id', $agent)->where('active_status', 1)->where('active_time', '>=', $this->begin)->where('active_time', '<=', $this->end);
	    		}])->get();
	    		foreach ($agentData as $key => $value) {
	    			$data['agent'][]	=  array('title' => $value->title, 'count' => $value->merchants_count);
	    		}
	    	}

	    	if($this->type == 'agent'){

	    		$agentInfo = \App\Buser::where('id', $request->agent_id)->first();

	    		if(!$agentInfo or empty($agentInfo) or $agentInfo->parent != $request->user->id){
	    			return response()->json(['error'=>['message' => '无此代理信息!']]);
	    		}

	    		$agent = $this->getAgent($request->agent_id);

	    		// 获取所有代理的
	    		$agent = $this->getAgent($request->user->id);
	    		$agentData = \App\Policy::withCount(['merchants' => function($query) use ($agent) {
	    			$query->whereIn('user_id', $agent)->where('active_status', 1)->where('active_time', '>=', $this->begin)->where('active_time', '<=', $this->end);
	    		}])->get();
	    		foreach ($agentData as $key => $value) {
	    			$data['agent'][]	=  array('title' => $value->title, 'count' => $value->merchants_count);
	    		}
	    	}

	    	return response()->json(['success'=>['message' => '获取成功!', 'data'=>$data]]);

    	} catch (\Exception $e) {

            return response()->json(['error'=>['message' => '系统错误,联系客服!']]);

        }
    }


    /**
     * @Author    Pudding
     * @DateTime  2020-06-06
     * @copyright [copyright]
     * @license   [license]
     * @version   [ 团队 - 业务详情 - 机具总数 ]
     * @param     Request     $request [description]
     * @return    [type]               [description]
     */
    public function AgentDetail(Request $request)
    {
    	try{

	    	$this->type = (!$request->type or $request->type =='self') ? 'self' : 'agent';

	    	// 如果查询的类型为agent 代表要查询直接下级的信息 所以agent_id 不能为空
	    	if($this->type == 'agent'){
	    		if(!$request->agent_id){
	    			return response()->json(['error'=>['message' => '缺少代理信息!']]);
	    		}
	    	}

	    	$data = array();

	    	if($this->type == 'self'){
	    		$selfData = \App\Brand::withCount(['merchants' => function($query) use ($request){
	    			$query->where('user_id', $request->user->id);
	    		}])->where('active', 1)->get();

	    		foreach ($selfData as $key => $value) {
	    			$data['self'][] = array('title' => $value->brand_name, 'count' => $value->merchants_count);
	    		}
	    		
				// 获取所有代理的
				$agent = $this->getAgent($request->user->id);
				$agentData = \App\Brand::withCount(['merchants' => function($query) use ($agent){
	    			$query->whereIn('user_id', $agent);
	    		}])->where('active', 1)->get();

				foreach ($agentData as $key => $value) {
					$data['agent'][]	=  array('title' => $value->brand_name, 'count' => $value->merchants_count);
				}
	    	}

	    	if($this->type == 'agent'){
				// 获取所有代理的
				$agent = $this->getAgent($request->agent_id);
				$agentData = \App\Brand::withCount(['merchants' => function($query) use ($agent){
	    			$query->whereIn('user_id', $agent);
	    		}])->where('active', 1)->get();

				foreach ($agentData as $key => $value) {
					$data['agent'][]	=  array('title' => $value->brand_name, 'count' => $value->merchants_count);
				}
	    	}

	    	return response()->json(['success'=>['message' => '获取成功!', 'data'=>$data]]);
    	} catch (\Exception $e) {

            return response()->json(['error'=>['message' => '系统错误,联系客服!']]);

        }
    }


    /**
     * @Author    Pudding
     * @DateTime  2020-07-21
     * @copyright [copyright]
     * @license   [license]
     * @version   [ 团队 - 业务详情 - 提现数据 ]
     * @param     Request     $request [description]
     */
    public function CashDetail(Request $request)
    {
    	try{

	    	$this->type = (!$request->type or $request->type =='self') ? 'self' : 'agent';

	    	// 如果查询的类型为agent 代表要查询直接下级的信息 所以agent_id 不能为空
	    	if($this->type == 'agent'){
	    		if(!$request->agent_id){
	    			return response()->json(['error'=>['message' => '缺少代理信息!']]);
	    		}
	    	}

	    	$this->dateType = (!$request->dateType or $request->dateType == 'day') ? 'day' : 'month';

	    	if(!$request->date){

	    		$this->begin = $this->dateType == 'day' ? Carbon::today()->toDateTimeString() : Carbon::now()->firstOfMonth()->toDateTimeString();

	    		$this->end = $this->dateType == 'day' ? Carbon::tomorrow()->toDateTimeString() : Carbon::now()->addMonth(1)->firstOfMonth()->toDateTimeString();
	    	}else{

	    		$this->begin = Carbon::createFromFormat('Y-m', $request->date)->firstOfMonth()->toDateTimeString();

	    		$this->end 	 = Carbon::createFromFormat('Y-m', $request->date)->addMonth(1)->firstOfMonth()->toDateTimeString();
	    	}

	    	$data = array();


	    	if( $this->type  == 'self' ){
	    		$cash = \App\Cash::where('user_id', $request->user->id)->where('created_at', '>=', $this->begin)->where('created_at', '<=', $this->end);
	    		$selfCashData   = $cash->whereIn('cash_type', ['1', '2', '3', '4'])->sum('cash_money');
	    		$selfActiveData = $cash->whereIn('cash_type', ['5', '6', '7', '8'])->sum('cash_money');
	    		$selfStandDate  = $cash->whereIn('cash_type', ['9', '10'])->sum('cash_money');

	    		$data['self'][] = array( 'title'=>'结算分润', 'count' =>number_format($selfCashData / 100, 2, '.', ','));
	    		$data['self'][] = array( 'title'=>'激活返现', 'count' =>number_format($selfActiveData / 100, 2, '.', ','));
	    		$data['self'][] = array( 'title'=>'达标奖励', 'count' =>number_format($selfStandDate / 100, 2, '.', ','));
	    	
	    		$agent = $this->getAgent($request->user->id);
	    		$agentCash = \App\Cash::whereIn('user_id', $agent)->whereBetween('created_at', [$this->begin, $this->end]);
	    		$agentCashData	 = $agentCash->whereIn('cash_type', ['1', '2', '3', '4'])->sum('cash_money');
	    		$agentActiveData = $agentCash->whereIn('cash_type', ['5', '6', '7', '8'])->sum('cash_money');
	    		$agentStandDate  = $agentCash->whereIn('cash_type', ['9', '10'])->sum('cash_money');

	    		$data['agent'][] = array( 'title'=>'结算分润', 'count' =>number_format($agentCashData / 100, 2, '.', ','));
	    		$data['agent'][] = array( 'title'=>'激活返现', 'count' =>number_format($agentActiveData / 100, 2, '.', ','));
	    		$data['agent'][] = array( 'title'=>'达标奖励', 'count' =>number_format($agentStandDate / 100, 2, '.', ','));
	    	}

	    	if ($this->type  == 'agent') {

	    		$agent = $this->getAgent($request->agent_id);
	    		$agentCash = \App\Cash::whereIn('user_id', $agent)->whereBetween('created_at', [$this->begin, $this->end]);
	    		$agentCashData	 = $agentCash->whereIn('cash_type', ['1', '2', '3', '4'])->sum('cash_money');
	    		$agentActiveData = $agentCash->whereIn('cash_type', ['5', '6', '7', '8'])->sum('cash_money');
	    		$agentStandDate  = $agentCash->whereIn('cash_type', ['9', '10'])->sum('cash_money');

	    		$data['agent'][] = array( 'title'=>'结算分润', 'count' =>number_format($agentCashData / 100, 2, '.', ','));
	    		$data['agent'][] = array( 'title'=>'激活返现', 'count' =>number_format($agentActiveData / 100, 2, '.', ','));
	    		$data['agent'][] = array( 'title'=>'达标奖励', 'count' =>number_format($agentStandDate / 100, 2, '.', ','));
	    	
	    	}

	    	return response()->json(['success'=>['message' => '获取成功!', 'data'=>$data]]);

    	} catch (\Exception $e) {

            return response()->json(['error'=>['message' => '系统错误,联系客服!']]);

        }
    }



    /**
     * @Author    Pudding
     * @DateTime  2020-06-06
     * @copyright [copyright]
     * @license   [license]
     * @version   [ 团队 - 业务详情 - 团队总数 ]
     * @param     Request     $request [description]
     * @return    [type]               [description]
     */
    public function TeamDetail(Request $request)
    {
    	try{

	    	$this->type = (!$request->type or $request->type =='self') ? 'self' : 'agent';

	    	// 如果查询的类型为agent 代表要查询直接下级的信息 所以agent_id 不能为空
	    	if($this->type == 'agent'){
	    		if(!$request->agent_id){
	    			return response()->json(['error'=>['message' => '缺少代理信息!']]);
	    		}
	    	}

	    	$this->dateType = (!$request->dateType or $request->dateType == 'day') ? 'day' : 'month';

	    	if(!$request->date){

	    		$this->begin = $this->dateType == 'day' ? Carbon::today()->toDateTimeString() : Carbon::now()->firstOfMonth()->toDateTimeString();

	    		$this->end = $this->dateType == 'day' ? Carbon::tomorrow()->toDateTimeString() : Carbon::now()->addMonth(1)->firstOfMonth()->toDateTimeString();
	    	}else{

	    		$this->begin = Carbon::createFromFormat('Y-m', $request->date)->firstOfMonth()->toDateTimeString();

	    		$this->end 	 = Carbon::createFromFormat('Y-m', $request->date)->addMonth(1)->firstOfMonth()->toDateTimeString();
	    	}

	    	$data = array();

	    	if($this->type == 'self'){
	    		$sons =  \App\Buser::where('parent', $request->user->id)->where('created_at', '>=', $this->begin)->where('created_at', '<=', $this->end)->pluck('id')->toArray();

	    		$data['self'][] = array( 'title' => '直推伙伴', 'count' => count($sons) );

	    		$data['self'][] = array(
	    			'title'		  => '间推伙伴',
	    			'count' => \App\BuserParent::where('parents', 'like', "%\_".$request->user->id."\_%")->whereNotIn('user_id', $sons)->where('created_at', '>=', $this->begin)->where('created_at', '<=', $this->end)->count(),
	    		);
	    	}

	    	if($this->type == 'agent'){

	    		$agentInfo = \App\Buser::where('id', $request->agent_id)->first();

	    		if(!$agentInfo or empty($agentInfo) or $agentInfo->parent != $request->user->id){
	    			return response()->json(['error'=>['message' => '无此代理信息!']]);
	    		}

	    		$sons =  \App\Buser::where('parent', $request->agent_id)->where('created_at', '>=', $this->begin)->where('created_at', '<=', $this->end)->pluck('id')->toArray();

	    		$data['agent'][] = array( 'title' => '直推伙伴', 'first_count' => count($sons));

	    		$data['agent'][] = array(
	    			'title'	=> '间推伙伴',
	    			'count' => \App\BuserParent::where('parents', 'like', "%\_".$request->agent_id."\_%")->whereNotIn('user_id', $sons)->where('created_at', '>=', $this->begin)->where('created_at', '<=', $this->end)->count(),
	    		);
	    	}

        	return response()->json(['success'=>['message' => '获取成功!', 'data'=>$data]]);

    	} catch (\Exception $e) {

            return response()->json(['error'=>['message' => '系统错误,联系客服!']]);

        }
    }


    /**
     * @Author    Pudding
     * @DateTime  2020-06-06
     * @copyright [copyright]
     * @license   [license]
     * @version   [  团队 - 业务详情 - 商户总数 ] ]
     * @param     Request     $request [description]
     * @return    [type]               [description]
     */
    public function MercDetail(Request $request)
    {

    }


    /**
     * @Author    Pudding
     * @DateTime  2020-07-20
     * @copyright [copyright]
     * @license   [license]
     * @version   [ 团队 - 业务详情 - 台均总数 ]
     * @param     Request     $request [description]
     */
    public function AvgDetail(Request $request)
    {
    	try{

	    	$this->type = (!$request->type or $request->type =='self') ? 'self' : 'agent';

	    	// 如果查询的类型为agent 代表要查询直接下级的信息 所以agent_id 不能为空
	    	if($this->type == 'agent'){
	    		if(!$request->agent_id){
	    			return response()->json(['error'=>['message' => '缺少代理信息!']]);
	    		}
	    	}

	    	$this->dateType = (!$request->dateType or $request->dateType == 'month') ? 'month' : 'month';

	    	if(!$request->date){

	    		$this->begin = Carbon::now()->firstOfMonth()->toDateTimeString();

	    		$this->end   = Carbon::now()->addMonth(1)->firstOfMonth()->toDateTimeString();

	    	}else{

	    		$this->begin = Carbon::createFromFormat('Y-m', $request->date)->firstOfMonth()->toDateTimeString();

	    		$this->end 	 = Carbon::createFromFormat('Y-m', $request->date)->addMonth(1)->firstOfMonth()->toDateTimeString();
	    	}

	    	$data = array();

	    	if($this->type == 'self'){
	    		//DB::connection()->enableQueryLog();
	    		// 获取交易量
	    		$selfData = \App\Policy::withCount([
	    			'merchants' => function($query) use ($request){
	    				$query->where('user_id', $request->user->id);
	    			}
	    		])->get();
	    		
	    		foreach ($selfData as $key => $value) {
	    			// 获取交易总量
	    			$TradeCount = \App\Trade::whereHas('merchants_sn', function($query) use ($request, $value){
	    				$query->where('user_id', $request->user->id)
	    					->where('policy_id', $value->id)
	    					->where('trade_status', 1)
	    					->where('card_type', '!=', '借记卡')
	    					->where('trade_time', '>=', $this->begin)->where('trade_time', '<=', $this->end);
	    			})->sum('money');


	    			if($TradeCount === 0 or $value->merchants_count === 0){
	    				$avg = 0;
	    			}else{
	    				$avg = number_format( $TradeCount / $value->merchants_count / 100 , 2, '.', ',');
	    			}
	    			$data['self'][] = array('title' => $value->title, 'avg' => $avg);
	    		}

				// 获取所有代理的
				$agent = $this->getAgent($request->user->id);
				$agentData = \App\Policy::withCount([
	    			'merchants' => function($query) use ($agent){
	    				$query->whereIn('user_id', $agent);
	    			}
	    		])->get();

				foreach ($agentData as $key => $value) {
					// 获取交易总量
	    			$TradeCount = \App\Trade::whereHas('merchants_sn', function($query) use ($agent, $value){
	    				$query->whereIn('user_id', $agent)
	    					->where('policy_id', $value->id)
	    					->where('trade_status', 1)
	    					->where('card_type', '!=', '借记卡')
	    					->where('trade_time', '>=', $this->begin)->where('trade_time', '<=', $this->end);
	    			})->sum('money');

	    			if($TradeCount === 0 or $value->merchants_count === 0){
	    				$avg = 0;
	    			}else{
	    				$avg = number_format($TradeCount / $value->merchants_count / 100 , 2, '.', ',');
	    			}
					$data['agent'][]	=  array('title' => $value->title, 'avg' => $avg);
				}
	    	}


	    	if($this->type == 'agent'){
				// 获取所有代理的
				$agent = $this->getAgent($request->agent_id);

				$agentData = \App\Policy::withCount([
	    			'merchants' => function($query) use ($agent){
	    				$query->whereIn('user_id', $agent);
	    			}
	    		])->get();

				foreach ($agentData as $key => $value) {
					// 获取交易总量
	    			$TradeCount = \App\Trade::whereHas('merchants_sn', function($query) use ($agent, $value){
	    				$query->whereIn('user_id', $agent)
	    					->where('policy_id', $value->id)
	    					->where('trade_status', 1)
	    					->where('card_type', '!=', '借记卡')
	    					->where('trade_time', '>=', $this->begin)->where('trade_time', '<=', $this->end);
	    			})->sum('money');

	    			if($TradeCount === 0 or $value->merchants_count === 0){
	    				$avg = 0;
	    			}else{
	    				$avg = number_format($TradeCount / $value->merchants_count / 100 , 2, '.', ',');
	    			}
					$data['agent'][]	=  array('title' => $value->title, 'avg' => $avg);
				}
	    	}

	    	return response()->json(['success'=>['message' => '获取成功!', 'data'=>$data]]);

    	} catch (\Exception $e) {

            return response()->json(['error'=>['message' => '系统错误,联系客服!']]);
        }

    }


    /**
     * @Author    Pudding
     * @DateTime  2020-07-20
     * @copyright [copyright]
     * @license   [license]
     * @version   [ 获取当前会员的所有下级 ]
     * @return    [type]      [description]
     */
    public function getAgent($user)
    {
    	return \App\BuserParent::where('parents', 'like', "%\_".$user."\_%")->pluck('user_id')->toArray();
    }
}
