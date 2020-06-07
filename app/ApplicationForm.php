<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ApplicationForm extends Model
{
	// 黑名单
	protected $guarded = ['id'];
}
