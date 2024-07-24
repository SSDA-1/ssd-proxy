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
        Schema::create('promocodes', function (Blueprint $table) {
            $table->id();
            $table->timestamp('date_end')->nullable();
            $table->integer('max_activated');
            $table->integer('count_activated')->default(0);
            $table->tinyInteger('multi_activating')->default(0);
            $table->float('discount');
            $table->integer('min_quantity')->default(1);
            $table->integer('min_rent')->default(1);
            $table->string('name');
            $table->tinyInteger('is_active')->default(1);
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
        Schema::dropIfExists('promocodes');
    }
};
