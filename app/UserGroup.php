<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserGroup extends Model
{
    protected $table = 'user_group';

    // 关联到用户表
    public function users()
    {
    	return $this->hasMany('\App\Buser', 'group', 'id');
    }
}
