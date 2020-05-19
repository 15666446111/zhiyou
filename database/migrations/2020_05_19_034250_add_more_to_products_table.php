<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMoreToProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {

            $table->string('title')->comment('品牌名称')->after('id');

            $table->tinyInteger('active')->default(1)->comment('开启状态')->after('title');

            $table->integer('price')->comment('产品价格')->default(0)->after('active');

            $table->longText('content')->nullable()->comment('文章内容')->after('price');

            //
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            //
        });
    }
}
