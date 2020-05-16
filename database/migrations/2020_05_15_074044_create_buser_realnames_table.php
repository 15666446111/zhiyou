<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBuserRealnamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buser_realnames', function (Blueprint $table) {
            
            $table->bigIncrements('id');

            $table->integer('user_id')->comment('会员ID');

            $table->tinyInteger('status')->comment('实名状态')->default(0);

            $table->string('name')->nullable()->comment('姓名');

            $table->string('idcard')->nullable()->comment('身份证号');

            $table->string('card_before')->nullable()->comment('身份证正面照片');

            $table->string('card_after')->nullable()->comment('身份证反面照片');

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
        Schema::dropIfExists('buser_realname');
    }
}
