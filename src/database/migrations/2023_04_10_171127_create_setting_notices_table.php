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
        Schema::create('setting_notices', function (Blueprint $table) {
            $table->id();
            $table->boolean('telegram_check')->default(false);
            $table->string('telegram_token')->nullable();
            $table->string('telegram_link')->nullable();
            $table->boolean('email_check')->default(false);
            $table->string('email')->nullable();
            $table->string('email_pass')->nullable();
            $table->boolean('third_email')->default(false);
            $table->string('third_email_host')->nullable();
            $table->string('third_email_port')->nullable();
            $table->string('third_email_username')->nullable();
            $table->string('third_email_password')->nullable();
            $table->string('third_email_encryption')->nullable();
            $table->string('third_email_address')->nullable();
            $table->timestamps();
        });

        DB::table('setting_notices')->insert([
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
        Schema::dropIfExists('setting_notices');
    }
};
