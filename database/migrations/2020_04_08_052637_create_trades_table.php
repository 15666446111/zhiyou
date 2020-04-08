<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTradesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trades', function (Blueprint $table) {

            $table->bigIncrements('id');

            $table->string('order')->comment('交易订单号')->nullable();

            $table->string('terminal')->comment('交易终端号')->nullable();

            $table->string('number')->comment('交易商户号')->nullable();

            $table->integer('money')->comment('交易金额')->default(0);

            $table->integer('rate')->comment('交易费率')->default(0);

            $table->integer('rate_money')->comment('交易手续费')->default(0);

            $table->integer('real_money')->comment('结算金额')->default(0);

            
            

            $table->tinyInteger('trade_status')->comment('交易状态')->default(1);

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
        Schema::dropIfExists('trades');
    }
}
