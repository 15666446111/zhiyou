<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MerchantStandard extends Model
{
    protected $table = 'merchant_standard';

    // 黑名单
	protected $guarded = [];

	/**
	 * @Author    Pudding
	 * @DateTime  2020-07-22
	 * @copyright [copyright]
	 * @license   [license]
	 * @version   [ 关联分润表]
	 * @return    [type]      [description]
	 */
	public function cashs()
	{
		return $this->hasMany('\App\Cash', 'order', 'order');
	}
}
