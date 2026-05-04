<?php
require 'vendor/autoload.php';

$log = \Illuminate\Support\Facades\DB::table('upload_logs')->latest('id')->first();
$path = 'storage/app/public/uploads/trade/RmkXmYWgCGYBQBGZjMMIlKfrKGT4rJU8FuL9K5nk.xlsx';

$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($path);
$rows = $spreadsheet->getActiveSheet()->toArray();

echo "HEADER: " . implode(' | ', $rows[0]) . PHP_EOL;
echo "ROW 1:  " . implode(' | ', $rows[1]) . PHP_EOL;