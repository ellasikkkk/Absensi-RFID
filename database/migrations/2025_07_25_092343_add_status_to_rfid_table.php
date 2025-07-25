<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rfid', function (Blueprint $table) {
            $table->string('status')->default('Tepat Waktu')->after('tag');
            $table->time('waktu')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rfid', function (Blueprint $table) {
            $table->dropColumn(['status', 'waktu']);
        });
    }
};
