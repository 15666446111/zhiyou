<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MachineLog extends Model
{

    protected $table = 'merchants_transfer_log';
 

    // 黑名单
    protected $guarded = [];
    
}
