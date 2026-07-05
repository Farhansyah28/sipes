<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImportSandbox extends Model
{
    protected $fillable = [
        'session_id', 'model_type', 'row_data', 'status', 'error_messages'
    ];

    protected $casts = [
        'row_data' => 'array',
    ];
}
