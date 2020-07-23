<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MachineLog extends Model
{

    protected $table = 'merchants_transfer_log';
 

    // 黑名单
    protected $guarded = [];
    


    public function user_a()
    {
    	return $this->belongsTo('App\Buser', 'user_id', 'id');
    }

    public function user_b()
    {
    	return $this->belongsTo('App\Buser', 'friend_id', 'id');
    }

    public function merchants()
    {
    	return $this->belongsTo('App\Merchant', 'merchant_id', 'id');
    }
}
