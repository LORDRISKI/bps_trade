<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('trade_data', function (Blueprint $table) {
            // Kolom bersama Ekspor & Impor
            $table->string('propinsi')->nullable()->after('bulan');
            $table->string('kode_negara')->nullable()->after('negara_tujuan');  // negara (kode)

            // Kolom khusus EKSPOR
            $table->string('neg_pil')->nullable()->after('kode_negara');        // neg pil
            $table->string('pel_riil')->nullable()->after('neg_pil');           // pel riil
            $table->string('deskhs8')->nullable()->after('pel_riil');           // deskhs8 (kategori)

            // Kolom khusus IMPOR
            $table->string('becx')->nullable()->after('deskhs8');               // Becx
            $table->string('neg')->nullable()->after('becx');                   // neg
            $table->string('deskr')->nullable()->after('neg');                  // deskr (kategori negara)
            $table->string('lama')->nullable()->after('deskr');                 // lama
            $table->string('nm_pelabuhan')->nullable()->after('lama');          // nm_pelabuhan
            $table->string('nm_negara')->nullable()->after('nm_pelabuhan');     // nm_negara
            $table->string('jenis_barang')->nullable()->after('nm_negara');     // Jenis (NM/dll)
            $table->string('nm_prop')->nullable()->after('jenis_barang');       // nm_prop
            $table->string('negara_asal')->nullable()->after('nm_prop');        // negara asal
            $table->string('pel_bong')->nullable()->after('negara_asal');       // pel_bong
        });
    }

    public function down(): void
    {
        Schema::table('trade_data', function (Blueprint $table) {
            $table->dropColumn([
                'propinsi', 'kode_negara', 'neg_pil', 'pel_riil', 'deskhs8',
                'becx', 'neg', 'deskr', 'lama', 'nm_pelabuhan', 'nm_negara',
                'jenis_barang', 'nm_prop', 'negara_asal', 'pel_bong',
            ]);
        });
    }
};