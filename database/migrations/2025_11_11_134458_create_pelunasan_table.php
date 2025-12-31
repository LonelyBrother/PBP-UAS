<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('pelunasan', function (Blueprint $table) {
            $table->id();
            $table->integer('no_pelunasan');
            $table->string('status');
            $table->string('daftar_motor');
            $table->date('tanggal_pelunasan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pelunasan');
    }
};
