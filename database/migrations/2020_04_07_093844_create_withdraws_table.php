<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWithdrawsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('withdraws', function (Blueprint $table) {

            $table->bigIncrements('id');

            $table->integer('user_id')->comment('提现用户');

            $table->integer('money')->comment('提现金额')->default(0);

            $table->integer('real_money')->comment('到账金额')->default(0);

            $table->integer('rate')->comment('提现费率')->default(0);

            $table->integer('rate_money')->comment('提现手续费')->default(0);

            $table->integer('single_rate')->comment('单笔提现费')->default(0);

            $table->smallInteger('status')->comment('处理状态')->default(0);

            $table->timeStamp('pay_time')->comment('处理时间')->nullable();

            $table->string('remark')->comment('备注信息')->nullable();

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
        Schema::dropIfExists('withdraws');
    }
}
