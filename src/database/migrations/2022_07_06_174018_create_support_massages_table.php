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
        Schema::create('support_massages', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('support_id')->constrained('supports');
            $table->longText('massage')->nullable();
            $table->boolean('admin')->nullable()->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('support_massages');
    }
};
