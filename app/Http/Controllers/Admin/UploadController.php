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
            $rows             = $nilaiPerTahun[$tahun];
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
            'file'  => 'required|file|mimes:csv,xlsx,xls,txt|max:51200', // FIX 1: naikkan limit jadi 50MB
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

        // FIX 2: Deteksi delimiter otomatis (koma atau titik koma)
        $firstLine = '';
        if (($f = fopen($filePath, 'r')) !== false) {
            $firstLine = fgets($f);
            fclose($f);
        }
        $delimiter = (substr_count($firstLine, ';') > substr_count($firstLine, ',')) ? ';' : ',';

        if (($handle = fopen($filePath, 'r')) !== false) {
            $header = null;
            while (($row = fgetcsv($handle, 0, $delimiter)) !== false) {
                if (!$header) {
                    $header = $this->normalizeHeader($row);
                    continue;
                }
                // Skip baris kosong
                if (empty(array_filter($row, fn($v) => trim((string)$v) !== ''))) continue;

                // FIX 3: Pastikan jumlah kolom sama dengan header
                if (count($row) !== count($header)) {
                    $failed++;
                    \Log::warning("Baris $total: jumlah kolom tidak cocok (" . count($row) . " vs " . count($header) . ")");
                    continue;
                }

                $total++;
                try {
                    $this->insertRow(array_combine($header, $row), $jenis, $logId);
                    $success++;
                } catch (\Exception $e) {
                    $failed++;
                    // FIX 4: Hapus "if ($failed === 1) throw $e" — jangan hentikan semua proses hanya karena 1 baris gagal
                    \Log::error("Insert gagal baris $total: " . $e->getMessage());
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
            foreach ($rows as $rowIndex => $row) {
                if (!$header) {
                    $header = $this->normalizeHeader(array_map('strval', $row));
                    continue;
                }
                if (empty(array_filter($row, fn($v) => trim((string)$v) !== ''))) continue;

                // FIX 3: Pastikan jumlah kolom sama dengan header
                if (count($row) !== count($header)) {
                    $failed++;
                    continue;
                }

                $total++;
                try {
                    $this->insertRow(array_combine($header, array_map('strval', $row)), $jenis, $logId);
                    $success++;
                } catch (\Exception $e) {
                    $failed++;
                    \Log::error("Insert Excel gagal baris $rowIndex: " . $e->getMessage());
                }
            }
        }
        return compact('total', 'success', 'failed');
    }

    /**
     * Normalisasi header: lowercase + trim + ganti spasi/tanda baca jadi underscore
     */
    private function normalizeHeader(array $row): array
    {
        return array_map(function ($h) {
            $h = strtolower(trim((string) $h));
            $h = preg_replace('/[\s\(\)\-\.]+/', '_', $h); // FIX 5: pakai regex agar lebih robust
            return trim($h, '_');
        }, $row);
    }

    private function insertRow(array $row, string $jenis, int $logId): void
    {
        // FIX 6: $get sekarang juga cek versi dengan spasi asli (sebelum normalisasi)
        $get = function (array $row, array $keys) {
            foreach ($keys as $key) {
                $variants = [
                    $key,
                    strtolower($key),
                    strtoupper($key),
                    str_replace('_', ' ', $key),       // "neg pil" dari "neg_pil"
                    str_replace('_', '.', $key),       // "negara.1" dari "negara_1"
                ];
                foreach ($variants as $k) {
                    if (array_key_exists($k, $row) && trim((string)$row[$k]) !== '') {
                        return $row[$k];
                    }
                }
            }
            return null;
        };

        if ($jenis === 'ekspor') {
            $this->insertEkspor($row, $logId, $get);
        } else {
            $this->insertImpor($row, $logId, $get);
        }
    }

    private function insertEkspor(array $row, int $logId, callable $get): void
    {
        TradeData::create([
            'upload_log_id' => $logId,
            'jenis'         => 'ekspor',
            'bulan'         => $get($row, ['bulan']),
            'tahun'         => $get($row, ['tahun']),
            'propinsi'      => $get($row, ['propinsi', 'propir']),
            'pelabuhan'     => $get($row, ['pelabuhan']),
            'hs_code'       => $get($row, ['hs8_btki2022', 'hs8_btki22', 'hs_code']),
            'kode_negara'   => $get($row, ['negara']),
            'berat_kg'      => $this->parseNumber($get($row, ['berat'])),
            'nilai_usd'     => $this->parseNumber($get($row, ['nilai'])),
            'negara_tujuan' => $get($row, ['negara_1', 'negara_1']),   // hasil normalisasi "negara.1"
            'deskhs8'       => $get($row, ['deskhs8']),
            'neg_pil'       => $get($row, ['neg_pil']),
            'pel_riil'      => $get($row, ['pel_riil']),
            'komoditas'     => $get($row, ['hs8_desk', 'hs8desk']),
            'keterangan'    => $get($row, ['keterangan']),
        ]);
    }

    private function insertImpor(array $row, int $logId, callable $get): void
    {
        TradeData::create([
            'upload_log_id' => $logId,
            'jenis'         => 'impor',
            'bulan'         => $get($row, ['bulan']),
            'tahun'         => $get($row, ['tahun']),
            'propinsi'      => $get($row, ['propinsi']),
            'pelabuhan'     => $get($row, ['pelabuhan']),
            'hs_code'       => $get($row, ['hs8_btki22', 'hs8_btki2022', 'hs_code']),
            'kode_negara'   => $get($row, ['negara']),
            'berat_kg'      => $this->parseNumber($get($row, ['berat'])),
            'nilai_usd'     => $this->parseNumber($get($row, ['nilai'])),
            'negara_tujuan' => $get($row, ['nm_negara']),
            'komoditas'     => $get($row, ['hs8desk', 'hs8_desk']),
            'becx'          => $get($row, ['becx']),
            'neg'           => $get($row, ['neg']),
            'deskr'         => $get($row, ['deskr']),
            'lama'          => $get($row, ['lama']),
            'nm_pelabuhan'  => $get($row, ['nm_pelabuhan']),
            'nm_negara'     => $get($row, ['nm_negara']),
            'jenis_barang'  => $get($row, ['jenis']),
            'nm_prop'       => $get($row, ['nm_prop']),
            'negara_asal'   => $get($row, ['negara_asal']),
            'pel_bong'      => $get($row, ['pel_bong']),
            'keterangan'    => $get($row, ['keterangan', 'lama']),
        ]);
    }

    private function parseNumber($value): ?float
    {
        if ($value === null || $value === '') return null;
        // FIX 7: Handle format angka dengan titik sebagai pemisah ribuan (1.000.000)
        $clean = trim((string)$value);
        // Jika ada koma sebagai desimal: 1.234,56 → 1234.56
        if (preg_match('/^\d{1,3}(\.\d{3})+(,\d+)?$/', $clean)) {
            $clean = str_replace('.', '', $clean);
            $clean = str_replace(',', '.', $clean);
        } else {
            $clean = str_replace([',', ' '], ['', ''], $clean);
        }
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