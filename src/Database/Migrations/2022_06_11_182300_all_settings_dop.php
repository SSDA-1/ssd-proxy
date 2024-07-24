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
        Schema::table('site_settings', function (Blueprint $table) {
            $table->string('name')->nullable();
            $table->string('logo')->nullable();
            $table->string('icon')->nullable();
            $table->string('ceo_desc')->nullable();
            $table->string('ceo_keywords')->nullable();
            $table->string('tamplate')->nullable();
            $table->string('telegram')->nullable();
            $table->string('email')->nullable();
            $table->string('facebook')->nullable();
            $table->string('skype')->nullable();
            $table->text('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('vkontakte')->nullable();
        });

        DB::table('site_settings')->insert([
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
        //
    }
};
