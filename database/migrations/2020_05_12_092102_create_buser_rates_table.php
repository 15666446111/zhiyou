<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBuserRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buser_rates', function (Blueprint $table) {

            $table->bigIncrements('id');
            
            $table->integer('user_id')->comment('所属用户');

            $table->integer('default_rate')->comment('默认费率');

            $table->integer('default_enjoy_rate')->comment('默认优享费率');

            $table->integer('default_code_rate')->comment('默认扫码费率');

            $table->integer('default_price')->comment('默认结算价');

            $table->integer('default_enjoy_price')->comment('默认优享结算价');

            $table->integer('default_code_price')->comment('默认扫码结算价');

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
        Schema::dropIfExists('buser_rates');
    }
}
