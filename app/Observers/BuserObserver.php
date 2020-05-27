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

        // 初始化钱包表
        \App\BuserWallet::create([ 'user_id'   =>  $buser->id ]);

        // 初始化实名表数据
        \App\BuserRealname::create([ 'user_id'   =>  $buser->id ]);    

        // 初始化用户费率和结算价
        \App\BuserRate::create([
            'user_id'               =>  $buser->id,
            'default_rate'          =>  config('fee.defaut'),
            'default_enjoy_rate'    =>  config('fee.default_enjoy'),
            'default_code_rate'     =>  config('fee.default_code'),
            'default_price'         =>  config('price.default'),
            'default_enjoy_price'   =>  config('price.default_enjoy'),
            'default_code_price'    =>  config('price.default_code'),
        ]);

        // 发送一条消息
        \App\BuserMessage::create([
            'user_id'               =>  $buser->id,
            'type'                  =>  'Register',
            'title'                 =>  '会员注册成功通知',
            'message_text'          =>  '通知内容',
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
