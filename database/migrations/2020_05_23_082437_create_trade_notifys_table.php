<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTradeNotifysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trade_notifys', function (Blueprint $table) {

            $table->bigIncrements('id');

            $table->string('title')->nullable()->comment('推送来源');

            $table->longText('content')->nullable()->comment('推送内容');

            $table->longText('other')->nullable()->comment('其他信息');

            $table->string('status_bak', '50')->default('success')->comment('推送状态');

            $table->boolean('is_queue')->default(0)->comment('是否推到队列');

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
        Schema::dropIfExists('trade_notifys');
    }
}
