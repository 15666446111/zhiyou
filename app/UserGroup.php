<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserGroup extends Model
{
    protected $table = 'user_group';


    /** 达标奖励与累积达标返现 **/
    public function getStandardAttribute($extra)
    {
        return array_values(json_decode($extra, true) ?: []);
    }

    public function setStandardAttribute($extra)
    {
        $this->attributes['standard'] = json_encode(array_values($extra));
    }

    public function getStandardCountAttribute($extra)
    {
        return array_values(json_decode($extra, true) ?: []);
    }

    public function setStandardCountAttribute($extra)
    {
        $this->attributes['standard_count'] = json_encode(array_values($extra));
    }
    /** 达标奖励与累积达标返现 end **/



    // 关联到用户表
    public function users()
    {
    	return $this->hasMany('\App\Buser', 'group', 'id');
    }
}
