<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddInfoToMerchantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('merchants', function (Blueprint $table) {
            
            $table->string("merchant_sn")->nullable()->after('merchant_terminal')->comment('Sn号');

            $table->boolean("active_status")->nullable()->after('merchant_sn')->comment('激活状态');

            $table->timeStamp("active_time")->nullable()->after('bind_time')->comment('激活时间');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('merchants', function (Blueprint $table) {
            //
        });
    }
}
