<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStandardSetToPoliciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('policies', function (Blueprint $table) {

            $table->text('default_standard_set')->nullable()->comment('普通用户达标设置')->after('vip_active_set');

            $table->text('vip_standard_set')->nullable()->comment('代理用户达标设置')->after('default_standard_set');

            $table->dropColumn([ 'active_return', 'standard', 'standard_count' ]);
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
