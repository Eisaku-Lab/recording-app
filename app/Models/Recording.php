<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recording extends Model
{
    protected $fillable = [
        'file_path',
        'original_name',
        'transcription',
        'summary',
        'summary_style',    // 追加
        'summary_length',   // 追加
        'summary_scene',    // 追加
    ];
}
