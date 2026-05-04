<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Kolom sudah ada dari migration sebelumnya
        // Migration ini dikosongkan agar tidak conflict
        Schema::table('trade_data', function (Blueprint $table) {
            $table->string('komoditas')->nullable()->change();
            $table->string('negara_tujuan')->nullable()->change();
            $table->integer('tahun')->nullable()->change();
        });
    }

    public function down(): void
    {
        //
    }
};