<?php

namespace App\Http\Controllers;

use App\Models\TradeData;
use Illuminate\Http\Request;

class TradeController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->only([
            'tahun', 'bulan', 'komoditas', 'negara_tujuan',
            'pelabuhan', 'jenis', 'hs_code',
            'berat_min', 'berat_max', 'nilai_min', 'nilai_max'
        ]);

        $query = TradeData::filter($filters)->orderBy('tahun', 'desc');
        $data  = $query->paginate(20)->withQueryString();

        $tahunList     = TradeData::selectRaw('DISTINCT tahun')->orderBy('tahun', 'desc')->pluck('tahun');
        $pelabuhanList = TradeData::selectRaw('DISTINCT pelabuhan')->whereNotNull('pelabuhan')->orderBy('pelabuhan')->pluck('pelabuhan');
        $negaraList    = TradeData::selectRaw('DISTINCT negara_tujuan')->orderBy('negara_tujuan')->pluck('negara_tujuan');

        $totalBerat   = TradeData::filter($filters)->sum('berat_kg');
        $totalNilai   = TradeData::filter($filters)->sum('nilai_usd');
        $totalRecords = TradeData::filter($filters)->count();

        return view('trade.index', compact(
            'data', 'filters',
            'tahunList', 'pelabuhanList', 'negaraList',
            'totalBerat', 'totalNilai', 'totalRecords'
        ));
    }

    public function export(Request $request)
    {
        $filters = $request->only([
            'tahun', 'bulan', 'komoditas', 'negara_tujuan',
            'pelabuhan', 'jenis', 'hs_code',
            'berat_min', 'berat_max', 'nilai_min', 'nilai_max'
        ]);

        $data     = TradeData::filter($filters)->orderBy('tahun', 'desc')->get();
        $jenis    = $filters['jenis'] ?? 'semua';
        $filename = 'trade_' . $jenis . '_' . now()->format('Ymd_His') . '.csv';

        return response()->stream(
            fn() => $this->writeCsv($data, $jenis),
            200,
            $this->csvHeaders($filename)
        );
    }

    public function exportSingle($id)
    {
        $row      = TradeData::findOrFail($id);
        $filename = 'trade_' . $row->tahun . '_' . $row->id . '.csv';

        $callback = function () use ($row) {
            $file  = fopen('php://output', 'w');
            $jenis = $row->jenis;
            fputs($file, "\xEF\xBB\xBF");
            fputcsv($file, $this->csvColumns($jenis), ';');
            fputcsv($file, $this->mapRow($row, $jenis), ';');
            fclose($file);
        };

        return response()->stream($callback, 200, $this->csvHeaders($filename));
    }

    private function writeCsv($data, string $jenis)
    {
        $file          = fopen('php://output', 'w');
        $headerWritten = false;
        fputs($file, "\xEF\xBB\xBF");

        foreach ($data as $row) {
            $rowJenis = ($jenis !== 'semua') ? $jenis : $row->jenis;
            if (!$headerWritten) {
                fputcsv($file, $this->csvColumns($rowJenis), ';');
                $headerWritten = true;
            }
            fputcsv($file, $this->mapRow($row, $rowJenis), ';');
        }

        if (!$headerWritten) {
            fputcsv($file, $this->csvColumns($jenis === 'semua' ? 'ekspor' : $jenis), ';');
        }

        fclose($file);
    }

    private function csvColumns(string $jenis): array
    {
        if ($jenis === 'impor') {
            return [
                'JREC', 'BULAN', 'TAHUN', 'PROPINSI', 'PELABUHAN',
                'HS8_BTKI22', 'NEGARA', 'BERAT', 'NILAI',
                'negara', 'Becx', 'neg', 'deskr', 'lama',
                'HS8desk', 'nm_pelabuhan', 'nm_negara',
                'Jenis', 'nm_prop', 'negara asal', 'pel_bong',
            ];
        }
        return [
            'jrec', 'bulan', 'tahun', 'propinsi', 'pelabuhan',
            'HS8_btki2022', 'negara', 'berat', 'nilai',
            'negara.1', 'deskhs8', 'neg pil', 'pel riil',
            'HS8_desk', 'Keterangan',
        ];
    }

    private function mapRow($row, string $jenis): array
    {
        if ($jenis === 'impor') {
            return [
                $row->id,
                $row->bulan ?? '',
                $row->tahun,
                $row->propinsi ?? '',
                $row->pelabuhan ?? '',
                $row->hs_code ?? '',
                $row->kode_negara ?? '',
                $row->berat_kg ?? '',
                $row->nilai_usd ?? '',
                $row->negara_tujuan ?? '',
                $row->becx ?? '',
                $row->neg ?? '',
                $row->deskr ?? '',
                $row->lama ?? '',
                $row->komoditas ?? '',
                $row->nm_pelabuhan ?? '',
                $row->nm_negara ?? '',
                $row->jenis_barang ?? '',
                $row->nm_prop ?? '',
                $row->negara_asal ?? '',
                $row->pel_bong ?? '',
            ];
        }
        return [
            $row->id,
            $row->bulan ?? '',
            $row->tahun,
            $row->propinsi ?? '',
            $row->pelabuhan ?? '',
            $row->hs_code ?? '',
            $row->kode_negara ?? '',
            $row->berat_kg ?? '',
            $row->nilai_usd ?? '',
            $row->negara_tujuan ?? '',
            $row->deskhs8 ?? '',
            $row->neg_pil ?? '',
            $row->pel_riil ?? '',
            $row->komoditas ?? '',
            $row->keterangan ?? '',
        ];
    }

    private function csvHeaders($filename): array
    {
        return [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];
    }
}