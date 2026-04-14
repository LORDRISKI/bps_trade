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
        if (!empty($filters['tahun'])) {
            $query->where('tahun', $filters['tahun']);
        }
        if (!empty($filters['komoditas'])) {
            $query->where('komoditas', 'like', '%' . $filters['komoditas'] . '%');
        }
        if (!empty($filters['negara_tujuan'])) {
            $query->where('negara_tujuan', 'like', '%' . $filters['negara_tujuan'] . '%');
        }
        if (!empty($filters['pelabuhan'])) {
            $query->where('pelabuhan', 'like', '%' . $filters['pelabuhan'] . '%');
        }
        if (!empty($filters['jenis'])) {
            $query->where('jenis', $filters['jenis']);
        }
        return $query;
    }
}
