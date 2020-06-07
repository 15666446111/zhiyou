<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeActiveStatusToMerchantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('merchants', function (Blueprint $table) {

            DB::statement('ALTER TABLE `merchants` CHANGE `active_status` `active_status` TINYINT(1) NULL DEFAULT 0 ');

            DB::statement('UPDATE `merchants` SET `active_status`= 0 WHERE `active_status` IS null');

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
