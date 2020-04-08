<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBusersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('busers', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('nickname')->comment('用户昵称')->nullable();

            $table->string('account')->comment('用户账号');

            $table->string('password')->comment('用户密码');

            $table->string('realname')->comment('真实姓名')->nullable();

            $table->string('phone')->comment('手机号')->nullable();

            $table->string('headimg')->comment('用户头像')->nullable();


            $table->bigInteger('parent')->comment('上级ID');

            $table->bigInteger('group')->comment('用户级别')->default(0);

            $table->bigInteger('blance')->comment('用户余额')->default(0);

            $table->bigInteger('score')->comment('用户积分')->default(0);

            // 默认开启用户
            $table->boolean('active')->comment('用户状态')->default(1);

            $table->boolean('blance_active')->comment('用户钱包状态')->default(1);

            $table->string('blance_bak')->comment('用户钱包冻结说明')->nullable();

            $table->string('last_ip')->comment('最后登录地址')->nullable();

            $table->timeStamp('last_time')->comment('最后登录时间')->nullable();

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
        Schema::dropIfExists('busers');
    }
}
