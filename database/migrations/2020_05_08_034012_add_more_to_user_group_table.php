<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMoreToUserGroupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_group', function (Blueprint $table) {

            $table->smallInteger('buy_count')->comment('采购多少台机器升级')->default(0)->after('count');

            $table->longText('standard')->comment('达标奖励')->nullable()->after('count');

            $table->longText('standard_count')->comment('累计达标返现奖励')->nullable()->after('count');
            //
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_group', function (Blueprint $table) {
            //
        });
    }
}
