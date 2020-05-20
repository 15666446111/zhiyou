<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserPoliciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_policies', function (Blueprint $table) {

            $table->bigIncrements('id');

            $table->string('user_id')->comment('用户id');

            $table->string('policy_id')->comment('政策id');

            $table->integer('sett_price')->comment('结算价')->default(0);

            $table->longText('active_return')->comment('激活返现')->nullable();

            $table->longText('standard')->comment('达标奖励')->nullable();

            $table->longText('standard_count')->comment('累计达标返现奖励')->nullable();

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
        Schema::dropIfExists('user_policies');
    }
}
