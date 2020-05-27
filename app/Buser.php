<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Buser extends Model
{

	// 黑名单
	protected $guarded = [];

	public function groups()
	{
		return $this->belongsTo('\App\UserGroup', 'group', 'id');
	}


	/**
	 * [merchants 关联钱包信息表]
	 * @author Pudding
	 * @DateTime 2020-04-10T15:33:52+0800
	 * @return   [type]                   [description]
	 */
 	public function wallets()
 	{
 		return $this->hasOne('\App\BuserWallet', 'user_id', 'id');
 	}


	/**
	 * [merchants 关联上级信息表]
	 * @author Pudding
	 * @DateTime 2020-04-10T15:33:52+0800
	 * @return   [type]                   [description]
	 */
 	public function user_parent()
 	{
 		return $this->hasOne('\App\BuserParent', 'user_id', 'id');
 	}


	/**
	 * [merchants 关联商户模型]
	 * @author Pudding
	 * @DateTime 2020-04-10T15:33:52+0800
	 * @return   [type]                   [description]
	 */
 	public function merchants()
 	{
 		return $this->hasMany('\App\Merchant', 'user_id', 'id');
 	}


 	/** 获取图片头像 **/
 	public function getHeadimgAttribute($value)
    {
    	return env("APP_URL")."/".$value;
    }


 	/**
 	 * [getParentStr 获取会员的所有上级]
 	 * @author Pudding
 	 * @DateTime 2020-04-13T16:47:17+0800
 	 * @param    [type]                   	$id [会员唯一标识]
 	 * @return   [string]                       [返回字符串类型]
 	 */
 	public static function getParentStr($id, $parents = "")
 	{
 		$User = \App\Buser::where('id', $id)->first();

 		if(!$User or empty($User)) return $parents;

 		$parents .= "_".$User->id."_,";

 		return $User->parent > 0 ? self::getParentStr($User->parent, $parents) : $parents;
 	}
}
