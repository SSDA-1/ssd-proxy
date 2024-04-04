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
        Schema::table('history_operations', function (Blueprint $table) {
            $table->decimal('balance_before')->nullable();
            $table->decimal('balance_after')->nullable();
            $table->string('country')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('history_operations', function (Blueprint $table) {
            $table->dropColumn('balance_before');
            $table->dropColumn('balance_after');
            $table->dropColumn('country');
        });
    }
};
