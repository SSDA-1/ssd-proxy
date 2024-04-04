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
        Schema::create('setting_krakens', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('integration_login')->nullable();
            $table->string('integration_password')->nullable();
            $table->string('integration_ip')->nullable();
            $table->integer('proxy_mounth')->nullable();
            $table->integer('proxy_all_price')->nullable();
            $table->integer('proxy_privat_price')->nullable();
            $table->integer('proxy_two_sel_count')->nullable();
            $table->integer('proxy_three_sel_count')->nullable();
            $table->integer('proxy_two_sel_period')->nullable();
            $table->integer('proxy_three_sel_period')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('setting_krakens');
    }
};
