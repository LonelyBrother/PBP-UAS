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
        Schema::table('motor_listrik', function (Blueprint $table) {
            $table->string('gambar')->nullable();
        });
        Schema::table('motor_matic', function (Blueprint $table) {
            $table->string('gambar')->nullable();
        });
        Schema::table('motor_sport', function (Blueprint $table) {
            $table->string('gambar')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

    }
};
