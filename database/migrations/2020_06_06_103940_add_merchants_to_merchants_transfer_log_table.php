<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMerchantsToMerchantsTransferLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('merchants_transfer_log', function (Blueprint $table) {
            
            $table->string("merchant_id")->after('friend_id')->comment('终端id');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('merchants_transfer_log', function (Blueprint $table) {
            //
        });
    }
}
