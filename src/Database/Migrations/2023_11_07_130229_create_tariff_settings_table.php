<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tariff_settings', function (Blueprint $table) {
            $table->id();
            $table->string('type_proxy')->nullable();
            $table->integer('type_tariff')->default(0);
            $table->json('days_tariff')->nullable();
            $table->integer('max_days')->nullable();
            $table->json('tariff')->nullable();
            $table->string('default_country')->nullable();
            $table->tinyInteger('proxy_discount')->default(0);
            $table->tinyInteger('days_discount')->default(0);
            $table->tinyInteger('proxy_pairs_discount')->default(0);
            $table->tinyInteger('promocode_discount')->default(0);
            $table->timestamps();
        });

        DB::table('tariff_settings')->insert([
            ['created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tariff_settings');
    }
};
