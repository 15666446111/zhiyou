<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddActiveinfoToPoliciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('policies', function (Blueprint $table) {
            //
            //
            $table->smallInteger('default_active')->default(0)->comment('直推激活奖励')->after('indirect_push');

            $table->smallInteger('indirect_active')->default(0)->comment('间推激活奖励')->after('default_active');

            $table->string('default_active_set')->default(0)->comment('普通用户返现设置')->after('indirect_active');

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
        Schema::table('policies', function (Blueprint $table) {
            //
        });
    }
}
