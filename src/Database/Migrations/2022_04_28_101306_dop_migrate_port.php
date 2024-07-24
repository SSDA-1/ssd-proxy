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
        Schema::table('modems', function (Blueprint $table) {
            $table->string('type_pay');
            $table->string('max_users');
            $table->integer('id_kraken')->nullable();
            $table->string('time_change')->nullable();
            $table->tinyInteger('locked_ip_type_change')->nullable();
            $table->string('reconnect_type_fake')->nullable();
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
