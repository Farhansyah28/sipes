<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Tenantable;

class KasPesantren extends Model
{
    use HasFactory, Tenantable;

    protected $fillable = [
        'tenant_id',
        'tanggal',
        'tipe',
        'kategori',
        'nominal',
        'keterangan',
        'referensi_type',
        'referensi_id',
    ];

    public function referensi()
    {
        return $this->morphTo();
    }
}
