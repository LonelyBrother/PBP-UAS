<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWilayahNamaToPembeliTable extends Migration
{
    public function up()
    {
        Schema::table('pembeli', function (Blueprint $table) {
            $table->string('provinsi_nama', 100)->nullable()->after('provinsi_id');
            $table->string('kabupaten_nama', 100)->nullable()->after('kabupaten_id');
            $table->string('kecamatan_nama', 100)->nullable()->after('kecamatan_id');
        });
    }

    public function down()
    {
        Schema::table('pembeli', function (Blueprint $table) {
            $table->dropColumn(['provinsi_nama', 'kabupaten_nama', 'kecamatan_nama']);
        });
    }
}

