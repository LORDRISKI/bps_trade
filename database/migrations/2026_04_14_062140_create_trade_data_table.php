<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trade_data', function (Blueprint $table) {
            $table->id();
            $table->integer('tahun');
            $table->string('komoditas');
            $table->string('hs_code')->nullable();
            $table->string('negara_tujuan');
            $table->decimal('berat_kg', 20, 2)->nullable();
            $table->decimal('nilai_usd', 20, 2)->nullable();
            $table->string('pelabuhan')->nullable();
            $table->text('keterangan')->nullable();
            $table->string('jenis')->default('ekspor');
            $table->timestamps();
        });

        Schema::create('upload_logs', function (Blueprint $table) {
            $table->id();
            $table->string('filename');
            $table->string('original_name');
            $table->integer('total_rows')->default(0);
            $table->integer('success_rows')->default(0);
            $table->integer('failed_rows')->default(0);
            $table->enum('status', ['processing', 'done', 'failed'])->default('processing');
            $table->text('error_message')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trade_data');
        Schema::dropIfExists('upload_logs');
    }
};