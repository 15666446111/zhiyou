<?php

namespace App\Providers;

use App\Buser;
use App\Observers\BuserObserver;

use Encore\Admin\Config\Config;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Schema::defaultStringLength(191);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        /**
         * @version [<新增用户 / 用户注册 / 用户模型发生新增事件的时候 执行观察者 >] [<description>]
         */
        User::observe(BuserObserver::class);


        /**
         * [$table Admin 的Setting ]
         * @var [type]
         */
        $table = config('admin.extensions.config.table', 'admin_config');

        if (Schema::hasTable($table)) { Config::load(); }
    }
}
