<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MerchantStandard extends Model
{
    protected $table = 'merchant_standard';

    // 黑名单
	protected $guarded = [];
}
