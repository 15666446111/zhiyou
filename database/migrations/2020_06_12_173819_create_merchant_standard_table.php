<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMerchantStandardTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('merchant_standard', function (Blueprint $table) {

            $table->bigIncrements('id');

            $table->string('sn')->nullable()->comment('达标的机器');

            $table->smallInteger('policy')->nullable()->comment('达标的政策');

            $table->smallInteger('index')->nullable()->comment('达标的索引');

            $table->string('remark')->nullable()->comment('达标的情况');

            $table->string('bak')->nullable()->comment('达标的情况');

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
        Schema::dropIfExists('merchant_standard');
    }
}
