<?php

namespace App\Http\Controllers;

use App\Trade;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BindMerchantController extends Controller
{
    

	/**
	 * @Author    Pudding
	 * @DateTime  2020-05-26
	 * @copyright [商户绑定]
	 * @license   [license]
	 * @version   [version]
	 * @return    [type]      [description]
	 */
    public function bind(Trade $trade)
    {

    	$trade->merchants_sn->merchant_number  =  $trade->merchants_sn->merchant_number ??  $trade->merchant_id;

    	$trade->merchants_sn->merchant_terminal  =  $trade->merchants_sn->merchant_terminal ??  $trade->terminal;

    	$trade->merchants_sn->merchant_name    =  $trade->merchants_sn->merchant_name ?? $trade->merchant_name;

    	$trade->merchants_sn->bind_time 	   =  $trade->merchants_sn->bind_time ?? Carbon::now()->toDateTimeString();

    	$trade->merchants_sn->bind_status 	   =  1;

    	$trade->merchants_sn->save();
    }
}
