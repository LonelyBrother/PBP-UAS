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
        Schema::create('kecamatan', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary(); // ID API
            $table->unsignedBigInteger('kabupaten_id');
            $table->string('nama');
            $table->timestamps();

            $table->foreign('kabupaten_id')
                ->references('id')
                ->on('kabupaten')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('kecamatan');
    }
};
