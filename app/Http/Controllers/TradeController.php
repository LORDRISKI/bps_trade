<?php

namespace App\Http\Controllers;

use App\Models\TradeData;
use Illuminate\Http\Request;

class TradeController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->only(['tahun', 'komoditas', 'negara_tujuan', 'pelabuhan', 'jenis']);

        $query = TradeData::filter($filters)->orderBy('tahun', 'desc');

        $data = $query->paginate(20)->withQueryString();

        // For filter dropdowns
        $tahunList      = TradeData::selectRaw('DISTINCT tahun')->orderBy('tahun', 'desc')->pluck('tahun');
        $komoditasList  = TradeData::selectRaw('DISTINCT komoditas')->orderBy('komoditas')->pluck('komoditas');
        $negaraList     = TradeData::selectRaw('DISTINCT negara_tujuan')->orderBy('negara_tujuan')->pluck('negara_tujuan');
        $pelabuhanList  = TradeData::selectRaw('DISTINCT pelabuhan')->whereNotNull('pelabuhan')->orderBy('pelabuhan')->pluck('pelabuhan');

        // Summary stats
        $totalBerat     = $query->sum('berat_kg');
        $totalNilai     = $query->sum('nilai_usd');
        $totalRecords   = TradeData::filter($filters)->count();

        return view('trade.index', compact(
            'data', 'filters',
            'tahunList', 'komoditasList', 'negaraList', 'pelabuhanList',
            'totalBerat', 'totalNilai', 'totalRecords'
        ));
    }

    public function export(Request $request)
    {
        $filters = $request->only(['tahun', 'komoditas', 'negara_tujuan', 'pelabuhan', 'jenis']);
        $data = TradeData::filter($filters)->orderBy('tahun', 'desc')->get();

        $filename = 'trade_data_' . now()->format('Ymd_His') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($data) {
            $file = fopen('php://output', 'w');
            // BOM for Excel UTF-8
            fputs($file, "\xEF\xBB\xBF");
            fputcsv($file, ['Tahun', 'Jenis', 'HS Code', 'Komoditas', 'Negara Tujuan', 'Berat (Kg)', 'Nilai (USD)', 'Pelabuhan', 'Keterangan']);
            foreach ($data as $row) {
                fputcsv($file, [
                    $row->tahun,
                    strtoupper($row->jenis),
                    $row->hs_code,
                    $row->komoditas,
                    $row->negara_tujuan,
                    $row->berat_kg,
                    $row->nilai_usd,
                    $row->pelabuhan,
                    $row->keterangan,
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
