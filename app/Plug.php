<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Plug extends Model
{
    //
    //
    public function scopeApiGet($query)
    {
    	return $query->orderBy('sort', 'desc')->limit(config('base.plug_limit'))->select(['image_file', 'link']);
    }

    /* 返回带全部地址的图片*/
    public function getImageFileAttribute($value)
    {
    	return env("APP_URL")."/".$value;
    }
}
