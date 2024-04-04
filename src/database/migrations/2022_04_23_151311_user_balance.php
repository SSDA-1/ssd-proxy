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
        Schema::table('users', function (Blueprint $table) {
            $table->string('kraken_username')->nullable();
            $table->string('kraken_password')->nullable();
            $table->integer('id_kraken')->nullable();
            $table->decimal('balance')->default(0);
            $table->decimal('referral_balance')->default(0);
            $table->integer('mode')->default(0);
            $table->integer('sidebarmode')->default(0);
            $table->string('referral_code')->unique()->nullable();
            $table->string('referrals_count')->nullable();
            $table->decimal('refferals_balance')->nullable();
            $table->string('telegram_chat_id')->nullable();
            $table->text('telegram_auth_id')->nullable();
            $table->string('telegram_name')->nullable();
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
