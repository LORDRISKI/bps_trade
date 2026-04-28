<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TradeData;
use App\Models\UploadLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    public function index()
    {
        $logs = UploadLog::orderBy('created_at', 'desc')->paginate(10);

        // === STAT CARDS ===
        $totalEkspor       = TradeData::where('jenis', 'ekspor')->count();
        $totalImpor        = TradeData::where('jenis', 'impor')->count();
        $totalNilaiEkspor  = TradeData::where('jenis', 'ekspor')->sum('nilai_usd');
        $totalNilaiImpor   = TradeData::where('jenis', 'impor')->sum('nilai_usd');
        $totalUpload       = UploadLog::where('status', 'done')->count();
        $neracaPerdagangan = $totalNilaiEkspor - $totalNilaiImpor;

        // === GRAFIK: Nilai per Tahun ===
        $nilaiPerTahun = TradeData::selectRaw('tahun, jenis, SUM(nilai_usd) as total_nilai')
            ->whereNotNull('tahun')
            ->groupBy('tahun', 'jenis')
            ->orderBy('tahun')
            ->get()
            ->groupBy('tahun');

        $tahunList      = $nilaiPerTahun->keys()->toArray();
        $eksporPerTahun = [];
        $imporPerTahun  = [];
        foreach ($tahunList as $tahun) {
            $rows           = $nilaiPerTahun[$tahun];
            $eksporPerTahun[] = round($rows->where('jenis', 'ekspor')->sum('total_nilai') / 1e6, 2);
            $imporPerTahun[]  = round($rows->where('jenis', 'impor')->sum('total_nilai') / 1e6, 2);
        }

        // === TOP 8 NEGARA TUJUAN EKSPOR ===
        $topNegara = TradeData::selectRaw('negara_tujuan, SUM(nilai_usd) as total_nilai')
            ->where('jenis', 'ekspor')
            ->whereNotNull('negara_tujuan')
            ->groupBy('negara_tujuan')
            ->orderByDesc('total_nilai')
            ->limit(8)
            ->get();

        // === TOP 8 KOMODITAS EKSPOR ===
        $topKomoditas = TradeData::selectRaw('komoditas, SUM(nilai_usd) as total_nilai')
            ->where('jenis', 'ekspor')
            ->whereNotNull('komoditas')
            ->groupBy('komoditas')
            ->orderByDesc('total_nilai')
            ->limit(8)
            ->get();

        return view('admin.upload', compact(
            'logs',
            'totalEkspor', 'totalImpor',
            'totalNilaiEkspor', 'totalNilaiImpor',
            'totalUpload', 'neracaPerdagangan',
            'tahunList', 'eksporPerTahun', 'imporPerTahun',
            'topNegara', 'topKomoditas'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'file'  => 'required|file|mimes:csv,xlsx,xls,txt|max:10240',
            'jenis' => 'required|in:ekspor,impor',
        ]);

        $file         = $request->file('file');
        $originalName = $file->getClientOriginalName();
        $storedName   = $file->store('uploads/trade', 'public');

        $log = UploadLog::create([
            'filename'      => $storedName,
            'original_name' => $originalName,
            'status'        => 'processing',
        ]);

        try {
            $result = $this->processFile(storage_path('app/public/' . $storedName), $request->jenis, $log->id);

            $log->update([
                'total_rows'   => $result['total'],
                'success_rows' => $result['success'],
                'failed_rows'  => $result['failed'],
                'status'       => 'done',
            ]);

            return response()->json([
                'success' => true,
                'message' => "Berhasil import {$result['success']} dari {$result['total']} baris data.",
                'log_id'  => $log->id,
            ]);
        } catch (\Exception $e) {
            $log->update([
                'status'        => 'failed',
                'error_message' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal memproses file: ' . $e->getMessage(),
            ], 422);
        }
    }

    private function processFile(string $filePath, string $jenis, int $logId): array
    {
        $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        if (in_array($ext, ['xlsx', 'xls'])) {
            return $this->processExcel($filePath, $jenis, $logId);
        }
        return $this->processCsv($filePath, $jenis, $logId);
    }

    private function processCsv(string $filePath, string $jenis, int $logId): array
    {
        $total = $success = $failed = 0;
        if (($handle = fopen($filePath, 'r')) !== false) {
            $header = null;
            while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                if (!$header) {
                    $header = array_map('strtolower', array_map('trim', $row));
                    $header = array_map(fn($h) => str_replace([' ', '(', ')'], ['_', '', ''], $h), $header);
                    continue;
                }
                $total++;
                try {
                    $this->insertRow(array_combine($header, $row), $jenis, $logId);
                    $success++;
                } catch (\Exception $e) {
                    $failed++;
                }
            }
            fclose($handle);
        }
        return compact('total', 'success', 'failed');
    }

    private function processExcel(string $filePath, string $jenis, int $logId): array
    {
        if (!class_exists('\PhpOffice\PhpSpreadsheet\IOFactory')) {
            throw new \Exception('PhpSpreadsheet tidak terinstall. Jalankan: composer require phpoffice/phpspreadsheet');
        }
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($filePath);
        $total = $success = $failed = 0;
        foreach ($spreadsheet->getAllSheets() as $sheet) {
            $rows   = $sheet->toArray();
            $header = null;
            foreach ($rows as $row) {
                if (!$header) {
                    $header = array_map('strtolower', array_map('trim', array_map('strval', $row)));
                    $header = array_map(fn($h) => str_replace([' ', '(', ')'], ['_', '', ''], $h), $header);
                    continue;
                }
                if (empty(array_filter($row))) continue;
                $total++;
                try {
                    $this->insertRow(array_combine($header, array_map('strval', $row)), $jenis, $logId);
                    $success++;
                } catch (\Exception $e) {
                    $failed++;
                }
            }
        }
        return compact('total', 'success', 'failed');
    }

    private function insertRow(array $row, string $jenis, int $logId): void
    {
        $get = function (array $row, array $keys) {
            foreach ($keys as $key) {
                if (isset($row[$key]) && $row[$key] !== '') return $row[$key];
                if (isset($row[strtolower($key)]) && $row[strtolower($key)] !== '') return $row[strtolower($key)];
                if (isset($row[strtoupper($key)]) && $row[strtoupper($key)] !== '') return $row[strtoupper($key)];
            }
            return null;
        };

        $negara    = $get($row, ['nm_negara', 'negara asal', 'deskr']) ?? $get($row, ['negara', 'NEGARA', 'country']);
        $pelabuhan = $get($row, ['nm_pelabuhan', 'pel riil', 'pel_bong']) ?? $get($row, ['pelabuhan', 'PELABUHAN', 'port']);
        $komoditas = $get($row, ['HS8_desk', 'deskhs8', 'deskr', 'HS8desk']) ?? $get($row, ['komoditas', 'commodity']);

        TradeData::create([
            'upload_log_id' => $logId,
            'tahun'         => $get($row, ['tahun', 'TAHUN', 'year']),
            'komoditas'     => $komoditas,
            'hs_code'       => $get($row, ['HS8_btki2022', 'HS8_BTKI22', 'hs_code', 'hs']),
            'negara_tujuan' => $negara,
            'berat_kg'      => $this->parseNumber($get($row, ['berat', 'BERAT', 'berat_kg', 'weight'])),
            'nilai_usd'     => $this->parseNumber($get($row, ['nilai', 'NILAI', 'nilai_usd', 'value'])),
            'pelabuhan'     => $pelabuhan,
            'keterangan'    => $get($row, ['Keterangan', 'keterangan', 'Jenis', 'lama']),
            'jenis'         => $jenis,
        ]);
    }

    private function parseNumber($value): ?float
    {
        if ($value === null || $value === '') return null;
        $clean = str_replace([',', ' '], ['', ''], $value);
        return is_numeric($clean) ? (float) $clean : null;
    }

    public function destroy($id)
    {
        $log = UploadLog::findOrFail($id);
        TradeData::where('upload_log_id', $log->id)->delete();
        Storage::disk('public')->delete($log->filename);
        $log->delete();
        return redirect()->back()->with('success', 'Log upload dan data terkait berhasil dihapus.');
    }

    public function downloadTemplate()
    {
        $filename = 'template_trade_data.csv';
        $callback = function () {
            $f = fopen('php://output', 'w');
            fputs($f, "\xEF\xBB\xBF");
            fputcsv($f, ['tahun', 'komoditas', 'hs_code', 'negara_tujuan', 'berat_kg', 'nilai_usd', 'pelabuhan', 'keterangan']);
            fputcsv($f, ['2024', 'Minyak Kelapa Sawit', '1511.10', 'India', '1000000', '850000', 'Tanjung Priok', 'Contoh data']);
            fclose($f);
        };
        return response()->stream($callback, 200, [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ]);
    }
}