<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cash extends Model
{
    protected $table = 'cashs';

	// 黑名单
	protected $guarded = ['id'];
}
