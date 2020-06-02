<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddInfoToTradesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trades', function (Blueprint $table) {

            $table->bigInteger("notify_id")->after('id')->comment('通知id');

            $table->string("batch_no")->nullable()->after('order')->comment('批次号');

            $table->string("terminal_no")->nullable()->after('batch_no')->comment('终端流水号');

            $table->string("merchant_id")->nullable()->after('terminal')->comment('商户号');

            $table->string("agt_merchant_id")->nullable()->after('merchant_id')->comment('渠道商户号');

            $table->string("agt_merchant_name")->nullable()->after('agt_merchant_id')->comment('渠道商户名称');

            $table->string("agt_merchant_level")->nullable()->after('agt_merchant_name')->comment('渠道商级别');

            $table->string("merchant_sn")->nullable()->after('agt_merchant_level')->comment('Sn号');

            $table->string("merchant_name")->nullable()->after('merchant_sn')->comment('商户编号名称');

            $table->string("fee_type")->nullable()->after('rate_money')->comment('手续费类型');

            $table->string("card_type")->nullable()->after('fee_type')->comment('交易卡类型');

            $table->string("card_number")->nullable()->after('card_type')->comment('交易卡号');

            $table->string("trade_type")->nullable()->after('card_number')->comment('交易类型');

            $table->string("collection_type")->nullable()->after('trade_type')->comment('收款类型');

            $table->string("audit_status")->nullable()->after('trade_status')->comment('清算状态');

            $table->string("is_sim")->nullable()->after('audit_status')->comment('流量卡费');

            $table->string("stl_type")->nullable()->after('is_sim')->comment('结算标示');

            $table->string("scan_flag")->nullable()->after('stl_type')->comment('正反扫标识');

            $table->string("clr_flag")->nullable()->after('scan_flag')->comment('调价');

            $table->string("is_auth_credit_card")->nullable()->after('clr_flag')->comment('是否本人卡');

            $table->string("trade_time")->nullable()->after('is_auth_credit_card')->comment('交易时间');

            $table->string("trade_actime")->nullable()->after('trade_time')->comment('交易接收时间');

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
        Schema::table('trades', function (Blueprint $table) {
            //
        });
    }
}
