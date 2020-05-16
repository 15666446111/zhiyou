<?php

namespace App\Observers;

use App\BuserRate;

class BuserRateObserver
{
    /**
     * Handle the buser "created" event.
     *
     * @param  \App\Buser  $buser
     * @return void
     */
    public function created(BuserRate $BuserRate)
    {
        // 初始化用户费率和结算价
        \App\BuserRateLog::create([
            'user_id'               =>  $BuserRate->user_id,
            'setting_before'        =>  json_encode(array()),
            'setting_after'    		=>  json_encode(array($BuserRate)),
            'setting_type'     		=>  'init'
        ]);
    }
}
