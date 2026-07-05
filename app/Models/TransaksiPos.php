<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransaksiPos extends Model
{
    use \App\Traits\Tenantable;
    protected $guarded = ['id'];
    protected $table = 'transaksi_pos';

    public function santri()
    {
        return $this->belongsTo(Santri::class);
    }

    public function kasir()
    {
        return $this->belongsTo(User::class, 'kasir_id');
    }

    public function details()
    {
        return $this->hasMany(DetailTransaksiPos::class, 'transaksi_pos_id');
    }
}
