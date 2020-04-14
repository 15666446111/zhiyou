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
}
