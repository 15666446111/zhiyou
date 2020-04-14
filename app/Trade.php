<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Trade extends Model
{

	/**
	 * [merchants 关联商户模型]
	 * @author Pudding
	 * @DateTime 2020-04-10T16:37:46+0800
	 * @return   [type]                   [description]
	 */
    public function merchants()
    {
    	return $this->belongsTo('\App\Merchant', 'terminal', 'merchant_terminal');
    }
}
