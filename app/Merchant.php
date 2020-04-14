<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Merchant extends Model
{
    
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
     * [tradess 管理交易模型]
     * @author Pudding
     * @DateTime 2020-04-10T16:35:15+0800
     * @return   [type]                   [description]
     */
    public function tradess()
    {
        return $this->hasMany('\App\Trade', 'terminal', 'merchant_terminal');
    }
}
