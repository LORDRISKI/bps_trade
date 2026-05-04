<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TradeData extends Model
{
    protected $table = 'trade_data';

    protected $fillable = [
        'upload_log_id',
        'jenis',
        'bulan',
        'tahun',
        'propinsi',
        'pelabuhan',
        'hs_code',
        'kode_negara',
        'berat_kg',
        'nilai_usd',
        'negara_tujuan',
        'komoditas',
        'keterangan',
        // Khusus Ekspor
        'neg_pil',
        'pel_riil',
        'deskhs8',
        // Khusus Impor
        'becx',
        'neg',
        'deskr',
        'lama',
        'nm_pelabuhan',
        'nm_negara',
        'jenis_barang',
        'nm_prop',
        'negara_asal',
        'pel_bong',
    ];

    protected $casts = [
        'tahun'     => 'integer',
        'bulan'     => 'integer',
        'berat_kg'  => 'float',
        'nilai_usd' => 'float',
    ];

    public function uploadLog()
    {
        return $this->belongsTo(UploadLog::class, 'upload_log_id');
    }

    public function scopeFilter($query, array $filters)
    {
        if (!empty($filters['jenis']))         $query->where('jenis', $filters['jenis']);
        if (!empty($filters['tahun']))         $query->where('tahun', $filters['tahun']);
        if (!empty($filters['bulan']))         $query->where('bulan', $filters['bulan']);
        if (!empty($filters['hs_code']))       $query->where('hs_code', 'like', '%' . $filters['hs_code'] . '%');
        if (!empty($filters['komoditas']))     $query->where('komoditas', 'like', '%' . $filters['komoditas'] . '%');
        if (!empty($filters['negara_tujuan'])) $query->where('negara_tujuan', 'like', '%' . $filters['negara_tujuan'] . '%');
        if (!empty($filters['pelabuhan']))     $query->where('pelabuhan', $filters['pelabuhan']);
        if (!empty($filters['berat_min']))     $query->where('berat_kg', '>=', $filters['berat_min']);
        if (!empty($filters['berat_max']))     $query->where('berat_kg', '<=', $filters['berat_max']);
        if (!empty($filters['nilai_min']))     $query->where('nilai_usd', '>=', $filters['nilai_min']);
        if (!empty($filters['nilai_max']))     $query->where('nilai_usd', '<=', $filters['nilai_max']);

        return $query;
    }
}