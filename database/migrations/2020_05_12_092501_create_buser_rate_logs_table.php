<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBuserRateLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buser_rate_logs', function (Blueprint $table) {

            $table->bigIncrements('id');

            $table->integer('user_id')->comment('所属用户');

            $table->text('setting_before')->comment('设置前费率');

            $table->text('setting_after')->comment('设置后费率');

            $table->string('setting_type')->comment('设置类型');

            $table->string('setting_user')->default(0)->comment('设置人员');

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
        Schema::dropIfExists('buser_rate_logs');
    }
}
