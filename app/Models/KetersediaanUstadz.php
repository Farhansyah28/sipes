<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KetersediaanUstadz extends Model
{
    use HasFactory, \App\Traits\Tenantable;

    protected $fillable = [
        'tenant_id',
        'ustadz_id',
        'hari',
        'jam_ke'
    ];

    public function ustadz()
    {
        return $this->belongsTo(Ustadz::class);
    }
}
