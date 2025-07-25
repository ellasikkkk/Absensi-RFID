<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('rfid', function (Blueprint $table) {
            $table->string('status')->default('Tepat Waktu'); // "Tepat Waktu" atau "Terlambat"
        });
    }

    public function down()
    {
        Schema::table('rfid', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
