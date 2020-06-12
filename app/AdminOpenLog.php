<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdminOpenLog extends Model
{
    
    protected $table = 'admin_operation_log';
 

    // 黑名单
    protected $guarded = [];

    public function admin_users()
	{
		return $this->belongsTo('\App\AdminUser', 'user_id', 'id');
	}
    
}
