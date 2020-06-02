<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{

    protected $table = 'bank';
 

    // 黑名单
    protected $guarded = [];
    
}
