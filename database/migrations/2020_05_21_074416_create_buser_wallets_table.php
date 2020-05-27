<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBuserWalletsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buser_wallets', function (Blueprint $table) {

            $table->bigIncrements('id');

            $table->integer('user_id')->comment('所属用户');

            $table->boolean('blance_active')->comment('用户钱包状态')->default(1);

            $table->string('blance_bak')->comment('用户钱包冻结说明')->nullable();

            $table->bigInteger('cash_blance')->comment('用户分润余额')->default(0);

            $table->bigInteger('return_blance')->comment('用户返现余额')->default(0);

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
        Schema::dropIfExists('buser_wallets');
    }
}
