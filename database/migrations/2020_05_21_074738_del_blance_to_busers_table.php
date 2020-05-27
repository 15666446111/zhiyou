<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DelBlanceToBusersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('busers', function (Blueprint $table) {
            $table->dropColumn([
                'blance', 'score', 'blance_active', 'blance_bak'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('busers', function (Blueprint $table) {
            //
        });
    }
}
