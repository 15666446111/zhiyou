<?php

namespace App\Http\Controllers\V1;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MerchantMoneyController
{
    /**
     * 终端号
     */
    protected $merchant;

    /**
     * 查询类型
     */
    protected $date;

    /**
	 * [$dateType 日期类型]
	 * @var [ month or day]
	 */
	protected $dateType;

    //初始化数据赋值
    public function __construct($merchant,$dateType,$date){

        $this->dateType = $dateType == "month" ? $dateType : "day";

        $this->date     = $date;
        
    	$this->merchant     = $merchant;
        // dd($this->merchant);
    }


    /**
     * 获取商户明细所有信息
     */
    public function getInfo()
    {

        $dt = Carbon::parse($this->date);
        
        $merchant = $this->merchant;
        
        $data = \App\Trade::select('card_type','card_number','trade_type','money','trade_time','trade_status')->where('terminal',$merchant);

        if($this->dateType == "month"){
    		$data->whereMonth('created_at', $dt->month)->whereYear('created_at', $dt->year);
    	}

    	if($this->dateType == "day"){
    		$data->whereDate('created_at', $this->date);
        }
        
        return $data->get();
    }

    
}
