<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
 	/** 获取图片头像 **/
 	public function getImageAttribute($value)
    {
    	return env("APP_URL")."/".$value;
    }

    
}
