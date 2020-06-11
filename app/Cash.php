<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cash extends Model
{
    protected $table = 'cashs';

	// 黑名单
	protected $guarded = ['id'];

	/**
	 * [merchants 关联商户模型]
	 * @author Pudding
	 * @DateTime 2020-04-10T16:37:46+0800
	 * @return   [type]                   [description]
	 */
    public function trades()
    {
    	return $this->belongsTo('\App\Trade', 'order', 'order');
    }

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
