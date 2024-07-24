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
        Schema::table('proxies', function (Blueprint $table) {
            $table->string('id_kraken')->nullable();
            $table->string('id_user_proxy_kraken')->nullable();
            $table->string('login_user_proxy_kraken')->nullable();
            $table->string('password_user_proxy_kraken')->nullable();
            $table->tinyInteger('autopay')->default(0);
            $table->integer('autopay_days')->nullable();
            $table->decimal('price')->nullable();
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
