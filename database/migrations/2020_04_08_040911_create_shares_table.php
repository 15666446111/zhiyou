<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSharesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shares', function (Blueprint $table) {

            $table->bigIncrements('id');

            $table->string('title')->comment('分享标题')->nullable();

            $table->string('image')->comment('分享地图')->nullable();

            $table->smallInteger('type')->comment('分享类型');

            $table->string('share_text')->comment('分享文案')->nullable();

            $table->smallInteger('code_width')->comment('二维码宽度')->default(100);

            $table->smallInteger('code_height')->comment('二维码高度')->default(100);

            $table->smallInteger('code_margin')->comment('二维码边距')->default(0);

            $table->smallInteger('pos_x')->comment('X轴位置')->default(0);

            $table->smallInteger('pos_y')->comment('Y轴位置')->default(0);

            $table->tinyInteger('active')->comment('展示状态')->default(1);

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
        Schema::dropIfExists('shares');
    }
}
