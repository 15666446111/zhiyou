<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCashTypeToCashsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cashs', function (Blueprint $table) {
            // 1、直营分润
            // 2、团队分润
            // 3、直推分润
            // 4、间推分润
            // 
            // 5、激活返现
            // 6、直推激活
            // 7、间推激活
            // 8、团队激活
            // 
            // 9、达标直营
            // 10、团队达标
            $table->smallInteger('cash_type')->default(1)->comment('分润类型')->after('status');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cashs', function (Blueprint $table) {
            //
        });
    }
}
