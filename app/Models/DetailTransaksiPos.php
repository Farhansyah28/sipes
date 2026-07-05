<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailTransaksiPos extends Model
{
    use \App\Traits\Tenantable;
    protected $guarded = ['id'];

    public function transaksiPos()
    {
        return $this->belongsTo(TransaksiPos::class, 'transaksi_pos_id');
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
}
