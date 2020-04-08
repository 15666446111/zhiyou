<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlugsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plugs', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('image_file')->comment('图片地址');

            $table->string('link')->comment('跳转地址')->nullable();

            $table->boolean('active')->comment('是否展示')->default(0);

            $table->smallInteger('sort')->comment('排序字段')->default(0);

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
        Schema::dropIfExists('plugs');
    }
}
