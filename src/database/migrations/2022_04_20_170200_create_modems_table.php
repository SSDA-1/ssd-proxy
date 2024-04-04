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
        Schema::create('modems', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('active')->nullable();
            $table->string('type')->nullable();
            $table->string('net_mode')->nullable();
            $table->string('is_osfp')->nullable();
            $table->string('osfp')->nullable();
            $table->string('ifname')->nullable();
            $table->string('reconnect_type')->nullable();
            $table->string('reconnect_interval')->nullable();
            $table->string('reconnect_min')->nullable();
            $table->jsonb('users');
            $table->timestamps();
        });
        
        Schema::table('proxies', function (Blueprint $table) {
            $table->unsignedBigInteger('modem_id');
            $table->foreign('modem_id')->references('id')->on('modems');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('modems');
    }
};
