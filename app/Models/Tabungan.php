<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tabungan extends Model
{
    use \App\Traits\Tenantable;
    protected $guarded = ['id'];

    public function santri()
    {
        return $this->belongsTo(Santri::class);
    }

    public function mutasiTabungans()
    {
        return $this->hasMany(MutasiTabungan::class);
    }
}
