<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('title')->nullable()->comment('文章标题');

            $table->tinyInteger('active')->default(1)->comment('开启状态');

            $table->string('images')->nullable()->comment('缩略图');

            $table->tinyInteger('type_id')->comment('文章类型');

            $table->string('verify')->default(0)->comment('是否审核');

            $table->longText('content')->nullable()->comment('文章内容');

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
        Schema::dropIfExists('articles');
    }
}
