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
    	$trade->merchants->merchant_number =  $trade->merchants->merchant_number ??  $trade->merchant_id;

    	$trade->merchants->merchant_name   =  $trade->merchants->merchant_name ?? $trade->merchant_name;

    	$trade->merchants->bind_time 	   =  $trade->merchants->bind_time ?? Carbon::now()->toDateTimeString();

    	$trade->merchants->bind_status 	   =  1;

    	$trade->merchants->save();
    }
}
