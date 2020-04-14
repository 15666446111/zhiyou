<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddApiTokenToBusersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('busers', function (Blueprint $table) {
            $table->string('api_token')->comment('API令牌')->nullable()->after('blance_bak');
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
            $table->dropColumn('api_token');
        });
    }
}
