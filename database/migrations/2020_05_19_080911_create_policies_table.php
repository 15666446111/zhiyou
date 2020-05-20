<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePoliciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('policies', function (Blueprint $table) {
            
            $table->bigIncrements('id');

            $table->string('title')->comment('政策活动');

            $table->tinyInteger('active')->comment('状态')->default(1);

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
        Schema::dropIfExists('policies');
    }
}
