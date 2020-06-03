<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBankTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank', function (Blueprint $table) {
            $table->increments('id');//主键自增ID

            $table->bigInteger("user_id")->comment('用户id');

            $table->string("name")->nullable()->comment('用户姓名');

            $table->string("bank")->nullable()->comment('银行卡号');

            $table->string("number")->nullable()->comment('身份证号');

            $table->string("open_bank")->nullable()->comment('开户行'); 

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
        Schema::dropIfExists('bank');
    }
}
