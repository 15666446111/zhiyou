<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Trade extends Model
{
	// 黑名单
	protected $guarded = ['id'];

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


    /**
     * [merchants 关联商户模型 通过SN关联]
     * @author Pudding
     * @DateTime 2020-04-10T16:37:46+0800
     * @return   [type]                   [description]
     */
    public function merchants_sn()
    {
        return $this->belongsTo('\App\Merchant', 'merchant_sn', 'merchant_sn');
    }

    /**
     * [tradess 关联分润模型]
     * @author Pudding
     * @DateTime 2020-04-10T16:35:15+0800
     * @return   [type]                   [description]
     */
    public function cashs()
    {
        return $this->hasMany('\App\Cash', 'order', 'order');
    }
}
