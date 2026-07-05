<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tagihan extends Model
{
    use \App\Traits\Tenantable;
    protected $guarded = ['id'];

    public function santri()
    {
        return $this->belongsTo(Santri::class);
    }

    public function kategoriTagihan()
    {
        return $this->belongsTo(KategoriTagihan::class);
    }

    public function pembayarans()
    {
        return $this->hasMany(Pembayaran::class);
    }
}
