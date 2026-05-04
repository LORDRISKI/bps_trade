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
        $filename = 'trade_data_' . now()->format('Ymd_His') . '.csv';

        return response()->stream(
            fn() => $this->writeCsv(collect([$data])->flatten()),
            200,
            $this->csvHeaders($filename)
        );
    }

    // ✅ Download 1 baris data berdasarkan ID
    public function exportSingle($id)
    {
        $row      = TradeData::findOrFail($id);
        $filename = 'trade_' . $row->tahun . '_' . $row->id . '.csv';

        $callback = function () use ($row) {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF");
            fputcsv($file, ['Tahun', 'Jenis', 'HS Code', 'Komoditas', 'Negara Tujuan', 'Berat (Kg)', 'Nilai (USD)', 'Pelabuhan', 'Keterangan']);
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
            fclose($file);
        };

        return response()->stream($callback, 200, $this->csvHeaders($filename));
    }

    private function writeCsv($data)
    {
        $file = fopen('php://output', 'w');
        fputs($file, "\xEF\xBB\xBF"); // BOM UTF-8
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
    }

    private function csvHeaders($filename)
    {
        return [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];
    }
}