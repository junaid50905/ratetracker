<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('profit_rates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('profit_id');
            $table->string('title');
            $table->string('rate');
            $table->foreign('profit_id')->references('id')->on('profits')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profit_rates');
    }
};
