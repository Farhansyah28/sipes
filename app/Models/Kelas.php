<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use \App\Traits\Tenantable;
    protected $guarded = ['id'];

    public function waliKelas()
    {
        return $this->belongsTo(Ustadz::class, 'wali_kelas_id');
    }
}
