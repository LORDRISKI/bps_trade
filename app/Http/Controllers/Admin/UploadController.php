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
        return view('admin.upload', compact('logs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,xlsx,xls,txt|max:10240',
            'jenis' => 'required|in:ekspor,impor',
        ]);

        $file = $request->file('file');
        $originalName = $file->getClientOriginalName();
        $storedName = $file->store('uploads/trade', 'public');

        $log = UploadLog::create([
            'filename'      => $storedName,
            'original_name' => $originalName,
            'status'        => 'processing',
        ]);

        try {
            $result = $this->processFile(storage_path('app/public/' . $storedName), $request->jenis);

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

    private function processFile(string $filePath, string $jenis): array
    {
        $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

        if (in_array($ext, ['xlsx', 'xls'])) {
            return $this->processExcel($filePath, $jenis);
        }

        return $this->processCsv($filePath, $jenis);
    }

    private function processCsv(string $filePath, string $jenis): array
    {
        $total = 0;
        $success = 0;
        $failed = 0;

        if (($handle = fopen($filePath, 'r')) !== false) {
            $header = null;
            while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                if (!$header) {
                    $header = array_map('strtolower', array_map('trim', $row));
                    // Normalize header names
                    $header = array_map(fn($h) => str_replace([' ', '(', ')'], ['_', '', ''], $h), $header);
                    continue;
                }

                $total++;
                try {
                    $mapped = array_combine($header, $row);
                    $this->insertRow($mapped, $jenis);
                    $success++;
                } catch (\Exception $e) {
                    $failed++;
                }
            }
            fclose($handle);
        }

        return compact('total', 'success', 'failed');
    }

    private function processExcel(string $filePath, string $jenis): array
{
    if (!class_exists('\PhpOffice\PhpSpreadsheet\IOFactory')) {
        throw new \Exception('PhpSpreadsheet tidak terinstall. Jalankan: composer require phpoffice/phpspreadsheet');
    }

    $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($filePath);

    $total = 0;
    $success = 0;
    $failed = 0;

    // Loop semua sheet
    foreach ($spreadsheet->getAllSheets() as $sheet) {
        $rows = $sheet->toArray();
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
                $mapped = array_combine($header, array_map('strval', $row));
                $this->insertRow($mapped, $jenis);
                $success++;
            } catch (\Exception $e) {
                $failed++;
            }
        }
    }

    return compact('total', 'success', 'failed');
}

    private function insertRow(array $row, string $jenis): void
{
    $get = function (array $row, array $keys) {
        foreach ($keys as $key) {
            // cek exact match dulu
            if (isset($row[$key]) && $row[$key] !== '') return $row[$key];
            // cek lowercase
            $lower = strtolower($key);
            if (isset($row[$lower]) && $row[$lower] !== '') return $row[$lower];
            // cek uppercase
            $upper = strtoupper($key);
            if (isset($row[$upper]) && $row[$upper] !== '') return $row[$upper];
        }
        return null;
    };

    // Ambil nama negara — pakai kolom teks bukan kode angka
    $negara = $get($row, ['nm_negara', 'negara asal', 'deskr']) 
           ?? $get($row, ['negara', 'NEGARA', 'country']);

    // Ambil nama pelabuhan — pakai nama bukan kode
    $pelabuhan = $get($row, ['nm_pelabuhan', 'pel riil', 'pel_bong'])
              ?? $get($row, ['pelabuhan', 'PELABUHAN', 'port']);

    // Komoditas dari deskripsi HS
    $komoditas = $get($row, ['HS8_desk', 'deskhs8', 'deskr', 'HS8desk'])
              ?? $get($row, ['komoditas', 'commodity']);

    TradeData::create([
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
        return is_numeric($clean) ? (float)$clean : null;
    }

    public function destroy($id)
    {
        $log = UploadLog::findOrFail($id);
        Storage::disk('local')->delete($log->filename);
        $log->delete();
        return redirect()->back()->with('success', 'Log upload dihapus.');
    }

    public function downloadTemplate()
    {
        $filename = 'template_trade_data.csv';
        $headers = ['Content-Type' => 'text/csv', 'Content-Disposition' => "attachment; filename=\"$filename\""];
        $callback = function () {
            $f = fopen('php://output', 'w');
            fputs($f, "\xEF\xBB\xBF");
            fputcsv($f, ['tahun', 'komoditas', 'hs_code', 'negara_tujuan', 'berat_kg', 'nilai_usd', 'pelabuhan', 'keterangan']);
            fputcsv($f, ['2024', 'Minyak Kelapa Sawit', '1511.10', 'India', '1000000', '850000', 'Tanjung Priok', 'Contoh data']);
            fclose($f);
        };
        return response()->stream($callback, 200, $headers);
    }
}
