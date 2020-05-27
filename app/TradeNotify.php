<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TradeNotify extends Model
{
	protected $table = 'trade_notifys';

	// 黑名单
	protected $guarded = [];
}
