<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->string('cooperation_tg')->nullable();
            $table->string('cooperation_email')->nullable();
            $table->string('cooperation_tel')->nullable();
            $table->boolean('qiwi_pay')->nullable()->default(false);
            $table->text('qiwi_public')->nullable();
            $table->text('qiwi_private')->nullable();
            $table->boolean('youmoney_pay')->nullable()->default(false);
            $table->text('youmoney_public')->nullable();
            $table->text('youmoney_private')->nullable();
            $table->boolean('demo_pay')->nullable()->default(false);
            $table->longText('google_m')->nullable();
            $table->longText('yandex_m')->nullable();
            $table->integer('deposit_percentage')->nullable();
            $table->boolean('minimum_withdrawal_amount')->nullable();
            $table->integer('min_replenishment_amount')->default(10);
            $table->string('promotional_materials')->nullable();
            $table->boolean('card_output')->nullable();
            $table->boolean('ecash_output')->nullable();
            $table->boolean('usdt_trc_20_output')->nullable();
            $table->boolean('capitalist_output')->nullable();
            $table->boolean('freekassa_pay')->nullable();
            $table->string('freekassa_id')->nullable();
            $table->text('freekassa_secret')->nullable();
            $table->boolean('betatransfer_pay')->nullable();
            $table->text('betatransfer_public')->nullable();
            $table->text('betatransfer_secret')->nullable();
            $table->boolean('capitalist_pay')->nullable();
            $table->text('capitalist_id')->nullable();
            $table->text('capitalist_secret')->nullable();
            $table->boolean('usdtchecker_pay')->nullable();
            $table->text('usdtchecker_token')->nullable();
            $table->text('usdtchecker_secret')->nullable();
            $table->boolean('referral_balance_enabled')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
