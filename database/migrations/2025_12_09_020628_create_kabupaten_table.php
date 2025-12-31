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
        Schema::create('kabupaten', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary(); // ID API
            $table->unsignedBigInteger('provinsi_id');
            $table->string('nama');
            $table->timestamps();

            $table->foreign('provinsi_id')->references('id')->on('provinsi')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('kabupaten');
    }
};
