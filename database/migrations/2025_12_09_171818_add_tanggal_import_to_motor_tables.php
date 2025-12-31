<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('motor_matic', function (Blueprint $table) {
            $table->timestamp('tanggal_import')->nullable()->after('perawatan');
        });

        Schema::table('motor_sport', function (Blueprint $table) {
            $table->timestamp('tanggal_import')->nullable()->after('perawatan');
        });

        Schema::table('motor_listrik', function (Blueprint $table) {
            $table->timestamp('tanggal_import')->nullable()->after('perawatan');
        });
    }

    public function down(): void
    {
        Schema::table('motor_matic', function (Blueprint $table) {
            $table->dropColumn('tanggal_import');
        });

        Schema::table('motor_sport', function (Blueprint $table) {
            $table->dropColumn('tanggal_import');
        });

        Schema::table('motor_listrik', function (Blueprint $table) {
            $table->dropColumn('tanggal_import');
        });
    }
};
