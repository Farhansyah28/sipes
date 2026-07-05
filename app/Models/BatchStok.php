<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BatchStok extends Model
{
    use \App\Traits\Tenantable;
    protected $guarded = ['id'];

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}
