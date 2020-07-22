<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Merchant extends Model
{
        
    // 黑名单
    protected $guarded = ['id'];

    /**
     * [busers 关联用户会员模型]
     * @author Pudding
     * @DateTime 2020-04-10T15:35:13+0800
     * @return   [type]                   [description]
     */
    public function busers()
    {
    	return $this->belongsTo('\App\Buser', 'user_id', 'id');
    }


    /**
     * [busers 关联终端品牌模型]
     * @author Pudding
     * @DateTime 2020-04-10T15:35:13+0800
     * @return   [type]                   [description]
     */
    public function brands()
    {
        return $this->belongsTo('\App\Brand', 'brand_id', 'id');
    }

    /**
     * [busers 关联终端活动政策模型]
     * @author Pudding
     * @DateTime 2020-04-10T15:35:13+0800
     * @return   [type]                   [description]
     */
    public function policys()
    {
        return $this->belongsTo('\App\Policy', 'policy_id', 'id');
    }

    /**
     * [tradess 管理交易模型]
     * @author Pudding
     * @DateTime 2020-04-10T16:35:15+0800
     * @return   [type]                   [description]
     */
    public function tradess()
    {
        return $this->hasMany('\App\Trade', 'terminal', 'merchant_terminal');
    }


    /**
     * [merchants 关联交易模型 通过SN关联]
     * @author Pudding
     * @DateTime 2020-04-10T16:37:46+0800
     * @return   [type]                   [description]
     */
    public function tradess_sn()
    {
        return $this->hasMany('\App\Trade', 'merchant_sn', 'merchant_sn');
    }
}
