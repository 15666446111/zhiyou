<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCashsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cashs', function (Blueprint $table) {

            $table->bigIncrements('id');

            $table->string('order')->comment('交易订单号')->nullable();

            $table->integer('user_id')->comment('分润用户');

            $table->integer('cash_money')->comment('分润金额')->default(0);

            $table->integer('status')->comment('分润状态')->default(1);

            $table->string('remark')->comment('分润备注')->nullable();

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
        Schema::dropIfExists('cashs');
    }
}
