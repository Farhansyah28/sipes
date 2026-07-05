<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiwayatKelas extends Model
{
    use \App\Traits\Tenantable;

    protected $fillable = [
        'tenant_id',
        'santri_id',
        'kelas_id',
        'tahun_ajaran_id',
        'tanggal_mulai',
    ];

    public function santri()
    {
        return $this->belongsTo(Santri::class);
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function tahun_ajaran()
    {
        return $this->belongsTo(TahunAjaran::class);
    }
}
