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
        Schema::table('pelunasan', function (Blueprint $table) {
            $table->unsignedBigInteger('pembeli_id')->after('no_pelunasan');
            $table->foreign('pembeli_id')->references('id')->on('pembeli')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pelunasan', function (Blueprint $table) {
            //
        });
    }
};
