<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMoreToBuserRealnamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('buser_realnames', function (Blueprint $table) {

            $table->string('bank_img_before')->nullable()->comment('手持银行卡或正面')->after('card_after');

            $table->string('bank_img_afer')->nullable()->comment('银行卡或反面')->after('bank_img_before');

            $table->string('bank_name')->nullable()->comment('结算银行')->after('bank_img_afer');

            $table->string('bank_number')->nullable()->comment('结算银行卡号')->after('bank_name');
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
        Schema::table('buser_realnames', function (Blueprint $table) {
            //
        });
    }
}
