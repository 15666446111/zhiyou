<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Withdraw extends Model
{
    protected $table = 'withdraws';

	// 黑名单
	protected $guarded = [];

    /**
     * @Author    Pudding
     * @DateTime  2020-06-10
     * @copyright [copyright]
     * @license   [license]
     * @version   [ 关联分润会员模型]
     * @return    [type]      [description]
     */
    public function users()
    {
    	return $this->belongsTo('\App\Buser', 'user_id', 'id');
    }
}
