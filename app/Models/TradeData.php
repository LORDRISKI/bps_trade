<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TradeData extends Model
{
    protected $table = 'trade_data';

    protected $fillable = [
        'tahun',
        'komoditas',
        'hs_code',
        'negara_tujuan',
        'berat_kg',
        'nilai_usd',
        'pelabuhan',
        'keterangan',
        'jenis',
    ];

    protected $casts = [
        'tahun'     => 'integer',
        'berat_kg'  => 'float',
        'nilai_usd' => 'float',
    ];
 public function scopeFilter($query, array $filters)
    {
        if (!empty($filters['jenis']))        $query->where('jenis', $filters['jenis']);
        if (!empty($filters['tahun']))        $query->where('tahun', $filters['tahun']);
        if (!empty($filters['bulan']))        $query->where('bulan', $filters['bulan']); // ✅
        if (!empty($filters['hs_code']))      $query->where('hs_code', 'like', '%'.$filters['hs_code'].'%'); // ✅
        if (!empty($filters['komoditas']))    $query->where('komoditas', 'like', '%'.$filters['komoditas'].'%');
        if (!empty($filters['negara_tujuan']))$query->where('negara_tujuan', 'like', '%'.$filters['negara_tujuan'].'%');
        if (!empty($filters['pelabuhan']))    $query->where('pelabuhan', $filters['pelabuhan']);
        if (!empty($filters['berat_min']))    $query->where('berat_kg', '>=', $filters['berat_min']); // ✅
        if (!empty($filters['berat_max']))    $query->where('berat_kg', '<=', $filters['berat_max']); // ✅
        if (!empty($filters['nilai_min']))    $query->where('nilai_usd', '>=', $filters['nilai_min']); // ✅
        if (!empty($filters['nilai_max']))    $query->where('nilai_usd', '<=', $filters['nilai_max']); // ✅

        return $query;
    }

}
