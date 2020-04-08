<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMerchantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('merchants', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->integer('user_id')->comment('所属用户');

            $table->string('user_phone')->comment('归属人电话')->nullable();

            $table->string('merchant_number')->comment('商户号')->nullable();

            $table->string('merchant_terminal')->comment('终端号')->nullable();

            $table->string('merchant_name')->comment('商户名称')->nullable();

            $table->tinyInteger('bind_status')->comment('绑定状态')->default(0);

            $table->timeStamp('bind_time')->comment('绑定时间')->nullable();

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
        Schema::dropIfExists('merchants');
    }
}
