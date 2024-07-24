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
        Schema::create('count_pairs_proxy_discounts', function (Blueprint $table) {
            $table->id();
            $table->integer('count_pairs');
            $table->float('discount_buy');
            $table->float('discount_extension');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('count_pairs_proxy_discounts');
    }
};
