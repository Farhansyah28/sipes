<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use \App\Traits\Tenantable;
    protected $guarded = ['id'];

    public function kategoriBarang()
    {
        return $this->belongsTo(KategoriBarang::class);
    }

    public function batchStoks()
    {
        return $this->hasMany(BatchStok::class);
    }
}
