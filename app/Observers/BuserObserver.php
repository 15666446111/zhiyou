<?php

namespace App\Observers;

use App\Buser;

class BuserObserver
{
    /**
     * Handle the buser "created" event.
     *
     * @param  \App\Buser  $buser
     * @return void
     */
    public function created(Buser $buser)
    {
        // 获得新增用户的上级集合 返回字符串
        $ParentStr = Buser::getParentStr($buser->parent);
        
        // 写入到关系表
        \App\BuserParent::create([
            'user_id'   =>  $buser->id,
            'parents'   =>  $ParentStr,
        ]);
    }

    /**
     * Handle the buser "updated" event.
     *
     * @param  \App\Buser  $buser
     * @return void
     */
    public function updated(Buser $buser)
    {
        //
    }

    /**
     * Handle the buser "deleted" event.
     *
     * @param  \App\Buser  $buser
     * @return void
     */
    public function deleted(Buser $buser)
    {
        //
    }

    /**
     * Handle the buser "restored" event.
     *
     * @param  \App\Buser  $buser
     * @return void
     */
    public function restored(Buser $buser)
    {
        //
    }

    /**
     * Handle the buser "force deleted" event.
     *
     * @param  \App\Buser  $buser
     * @return void
     */
    public function forceDeleted(Buser $buser)
    {
        //
    }
}
