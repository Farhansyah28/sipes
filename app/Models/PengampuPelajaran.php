<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengampuPelajaran extends Model
{
    use HasFactory, \App\Traits\Tenantable;

    protected $fillable = [
        'tenant_id',
        'ustadz_id',
        'mata_pelajaran_id',
        'kelas_id',
        'beban_jp'
    ];

    public function ustadz()
    {
        return $this->belongsTo(Ustadz::class);
    }

    public function mata_pelajaran()
    {
        return $this->belongsTo(MataPelajaran::class);
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }
}
