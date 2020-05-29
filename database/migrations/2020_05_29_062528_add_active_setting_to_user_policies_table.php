<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddActiveSettingToUserPoliciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_policies', function (Blueprint $table) {
            
            $table->string('default_active_set')->default(0)->comment('普通用户返现设置')->after('sett_price');

            $table->string('vip_active_set')->default(0)->comment('代理用户返现设置')->after('default_active_set');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_policies', function (Blueprint $table) {
            //
        });
    }
}
