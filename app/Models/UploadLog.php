<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UploadLog extends Model
{
    protected $table = 'upload_logs';

    protected $fillable = [
        'filename',
        'original_name',
        'total_rows',
        'success_rows',
        'failed_rows',
        'status',
        'error_message',
    ];

    /**
     * Relasi ke data trade yang diimport dari upload ini.
     */
    public function tradeData()
    {
        return $this->hasMany(TradeData::class, 'upload_log_id');
    }
}