<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TradeData;
use App\Models\UploadLog;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // === RINGKASAN UTAMA ===
        $totalEkspor     = TradeData::where('jenis', 'ekspor')->count();
        $totalImpor      = TradeData::where('jenis', 'impor')->count();
        $totalNilaiEkspor = TradeData::where('jenis', 'ekspor')->sum('nilai_usd');
        $totalNilaiImpor  = TradeData::where('jenis', 'impor')->sum('nilai_usd');
        $totalUpload     = UploadLog::where('status', 'done')->count();
        $neracaPerdagangan = $totalNilaiEkspor - $totalNilaiImpor;

        // === GRAFIK 1: Nilai Ekspor & Impor per Tahun ===
        $nilaiPerTahun = TradeData::selectRaw('tahun, jenis, SUM(nilai_usd) as total_nilai')
            ->whereNotNull('tahun')
            ->groupBy('tahun', 'jenis')
            ->orderBy('tahun')
            ->get()
            ->groupBy('tahun');

        $tahunList = $nilaiPerTahun->keys()->toArray();
        $eksporPerTahun = [];
        $imporPerTahun  = [];
        foreach ($tahunList as $tahun) {
            $rows = $nilaiPerTahun[$tahun];
            $eksporPerTahun[] = round($rows->where('jenis', 'ekspor')->sum('total_nilai') / 1e6, 2);
            $imporPerTahun[]  = round($rows->where('jenis', 'impor')->sum('total_nilai') / 1e6, 2);
        }

        // === GRAFIK 2: Top 10 Negara Tujuan Ekspor ===
        $topNegara = TradeData::selectRaw('negara_tujuan, SUM(nilai_usd) as total_nilai')
            ->where('jenis', 'ekspor')
            ->whereNotNull('negara_tujuan')
            ->groupBy('negara_tujuan')
            ->orderByDesc('total_nilai')
            ->limit(10)
            ->get();

        // === GRAFIK 3: Top 10 Komoditas Ekspor ===
        $topKomoditas = TradeData::selectRaw('komoditas, SUM(nilai_usd) as total_nilai, SUM(berat_kg) as total_berat')
            ->where('jenis', 'ekspor')
            ->whereNotNull('komoditas')
            ->groupBy('komoditas')
            ->orderByDesc('total_nilai')
            ->limit(10)
            ->get();

        // === GRAFIK 4: Ekspor vs Impor per Pelabuhan ===
        $topPelabuhan = TradeData::selectRaw('pelabuhan, jenis, SUM(nilai_usd) as total_nilai')
            ->whereNotNull('pelabuhan')
            ->groupBy('pelabuhan', 'jenis')
            ->orderByDesc('total_nilai')
            ->get()
            ->groupBy('pelabuhan')
            ->sortByDesc(fn($rows) => $rows->sum('total_nilai'))
            ->take(8);

        $pelabuhanLabels   = $topPelabuhan->keys()->toArray();
        $pelabuhanEkspor   = [];
        $pelabuhanImpor    = [];
        foreach ($pelabuhanLabels as $pel) {
            $rows = $topPelabuhan[$pel];
            $pelabuhanEkspor[] = round($rows->where('jenis', 'ekspor')->sum('total_nilai') / 1e6, 2);
            $pelabuhanImpor[]  = round($rows->where('jenis', 'impor')->sum('total_nilai') / 1e6, 2);
        }

        // === GRAFIK 5: Distribusi Berat per Tahun ===
        $beratPerTahun = TradeData::selectRaw('tahun, jenis, SUM(berat_kg) as total_berat')
            ->whereNotNull('tahun')
            ->groupBy('tahun', 'jenis')
            ->orderBy('tahun')
            ->get()
            ->groupBy('tahun');

        $eksporBeratPerTahun = [];
        $imporBeratPerTahun  = [];
        foreach ($tahunList as $tahun) {
            $rows = $beratPerTahun[$tahun] ?? collect();
            $eksporBeratPerTahun[] = round($rows->where('jenis', 'ekspor')->sum('total_berat') / 1e6, 2);
            $imporBeratPerTahun[]  = round($rows->where('jenis', 'impor')->sum('total_berat') / 1e6, 2);
        }

        // === LOG TERBARU ===
        $recentLogs = UploadLog::orderBy('created_at', 'desc')->limit(5)->get();

        return view('admin.dashboard', compact(
            'totalEkspor', 'totalImpor',
            'totalNilaiEkspor', 'totalNilaiImpor',
            'totalUpload', 'neracaPerdagangan',
            'tahunList', 'eksporPerTahun', 'imporPerTahun',
            'topNegara', 'topKomoditas',
            'pelabuhanLabels', 'pelabuhanEkspor', 'pelabuhanImpor',
            'eksporBeratPerTahun', 'imporBeratPerTahun',
            'recentLogs'
        ));
    }
}