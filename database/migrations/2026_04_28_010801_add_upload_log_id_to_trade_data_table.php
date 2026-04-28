<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('trade_data', function (Blueprint $table) {
            $table->unsignedBigInteger('upload_log_id')->nullable()->after('id');
            $table->foreign('upload_log_id')
                  ->references('id')
                  ->on('upload_logs')
                  ->onDelete('cascade'); // otomatis hapus saat log dihapus
        });
    }

    public function down(): void
    {
        Schema::table('trade_data', function (Blueprint $table) {
            $table->dropForeign(['upload_log_id']);
            $table->dropColumn('upload_log_id');
        });
    }
};