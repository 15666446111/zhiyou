<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BuserParent extends Model
{
    // 黑名单
	protected $guarded = [];


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
}
