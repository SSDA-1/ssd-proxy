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
        Schema::create('history_operations', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('type');
            $table->decimal('amount');
            $table->tinyText('notes')->nullable();
            $table->foreignId('user_id')->constrained('users');
            $table->integer('status')->nullable();
            $table->integer('quantity')->nullable();
            $table->integer('duration')->nullable();
            $table->text('billId')->nullable();
            $table->string('promocode')->nullable();
            $table->decimal('discount_amount')->nullable();
            $table->unsignedBigInteger('referred_by');
            $table->foreign('referred_by')->references('id')->on('users')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('history_operations');
    }
};
