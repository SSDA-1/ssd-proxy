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
        Schema::create('project_status', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('is_domain_active')->default(1);
            $table->tinyInteger('is_archive')->default(0);
            $table->tinyInteger('to_delete')->default(0);
            $table->timestamps();
        });

        DB::table('project_status')->insert([
            ['is_domain_active' => 0, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('project_status');
    }
};
