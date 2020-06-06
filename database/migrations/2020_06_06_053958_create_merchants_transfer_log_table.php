<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMerchantsTransferLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('merchants_transfer_log', function (Blueprint $table) {
            $table->increments('id');//主键自增ID

            $table->bigInteger("user_id")->comment('用户id');

            $table->bigInteger("friend_id")->nullable()->comment('直接下级id');

            $table->bigInteger("is_back")->nullable()->default(0)->comment('是否已经回拨');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('merchants_transfer_log');
    }
}
