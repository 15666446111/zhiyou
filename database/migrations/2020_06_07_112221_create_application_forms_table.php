<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApplicationFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('application_forms', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger("user_id")->comment('用户id');

            $table->bigInteger("agent_id")->comment('申请时最近的代理');

            $table->string('name')->nullable()->comment('申请人姓名');

            $table->string('phone')->nullable()->comment('申请人电话');

            $table->string('address')->nullable()->comment('申请人地址');

            $table->boolean('is_handle')->default(0)->comment('申请人电话');

            $table->timestamp('handle_time')->nullable()->comment('处理时间');

            $table->string('handle_temail')->nullable()->comment('装机的终端');

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
        Schema::dropIfExists('application_forms');
    }
}
