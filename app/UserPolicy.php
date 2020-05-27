<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserPolicy extends Model
{
	// 黑名单
	protected $guarded = [];

	/**
	 * @Author    Pudding
	 * @DateTime  2020-05-21
	 * @copyright [ 结算价]
	 * @license   [license]
	 * @version   [version]
	 * @param     [type]      $extra [description]
	 * @return    [type]             [description]
	 */
    public function getSettPriceAttribute($extra)
    {
        return array_values(json_decode($extra, true) ?: []);
    }
    public function setSettPriceAttribute($extra)
    {
        $this->attributes['sett_price'] = json_encode(array_values($extra));
    }


    /**
     * @Author    Pudding
     * @DateTime  2020-05-22
     * @copyright [关联用户模型]
     * @license   [license]
     * @version   [version]
     * @return    [type]      [description]
     */
    public function busers()
    {
    	return $this->belongsTo('\App\Buser', 'user_id', 'id');
    }


    /**
     * @Author    Pudding
     * @DateTime  2020-05-22
     * @copyright [关联用户模型]
     * @license   [license]
     * @version   [version]
     * @return    [type]      [description]
     */
    public function policys()
    {
    	return $this->belongsTo('\App\Policy', 'policy_id', 'id');
    }
}
