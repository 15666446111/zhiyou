<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Withdraw extends Model
{
    protected $table = 'withdraws';

	// 黑名单
	protected $guarded = [];
}
