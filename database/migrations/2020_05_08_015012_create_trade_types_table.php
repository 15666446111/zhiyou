<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTradeTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trade_types', function (Blueprint $table) {

            $table->bigIncrements('id');
                
            $table->string('type_name')->comment('交易类型');

            $table->string('type_value')->comment('类型代号');

            $table->tinyInteger('is_rewetting')->default(0)->comment('是否返润');

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
        Schema::dropIfExists('trade_types');
    }
}
