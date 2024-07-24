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
        Schema::table('advantags', function (Blueprint $table) {
            $table->string('title_en')->nullable();
            $table->string('description_en')->nullable();
        });

        Schema::table('faqs', function (Blueprint $table) {
            $table->string('question_en')->nullable();
            $table->string('answer_en')->nullable();
        });

        Schema::table('menus', function (Blueprint $table) {
            $table->string('name_en')->nullable();
        });

        Schema::table('news', function (Blueprint $table) {
            $table->string('name_en')->nullable();
            $table->string('detail_en')->nullable();
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->string('name_en')->nullable();
            $table->string('description_en')->nullable();
        });

        Schema::table('rules', function (Blueprint $table) {
            $table->string('text_en')->nullable();
        });

        Schema::table('process_logs', function (Blueprint $table) {
            $table->string('name_en')->nullable();
            $table->string('description_en')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('advantags', function (Blueprint $table) {
            $table->dropColumn('title_en');
            $table->dropColumn('description_en');
        });

        Schema::table('faqs', function (Blueprint $table) {
            $table->dropColumn('question_en');
            $table->dropColumn('answer_en');
        });

        Schema::table('menus', function (Blueprint $table) {
            $table->dropColumn('name_en');
        });

        Schema::table('news', function (Blueprint $table) {
            $table->dropColumn('name_en');
            $table->dropColumn('detail_en');
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->dropColumn('name_en');
            $table->dropColumn('description_en');
        });

        Schema::table('rules', function (Blueprint $table) {
            $table->dropColumn('text_en');
        });

        Schema::table('process_logs', function (Blueprint $table) {
            $table->dropColumn('name_en');
            $table->dropColumn('description_en');
        });
    }
};
