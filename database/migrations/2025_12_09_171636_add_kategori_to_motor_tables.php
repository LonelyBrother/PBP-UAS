<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('motor_matic', function (Blueprint $table) {
            $table->string('kategori')->default('matic')->after('brand');
        });

        Schema::table('motor_sport', function (Blueprint $table) {
            $table->string('kategori')->default('sport')->after('brand');
        });

        Schema::table('motor_listrik', function (Blueprint $table) {
            $table->string('kategori')->default('listrik')->after('brand');
        });
    }

    public function down(): void
    {
        Schema::table('motor_matic', function (Blueprint $table) {
            $table->dropColumn('kategori');
        });
        Schema::table('motor_sport', function (Blueprint $table) {
            $table->dropColumn('kategori');
        });
        Schema::table('motor_listrik', function (Blueprint $table) {
            $table->dropColumn('kategori');
        });
    }
};
