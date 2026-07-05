<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrangTua extends Model
{
    use \App\Traits\Tenantable;
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function santris()
    {
        return $this->hasMany(Santri::class, 'orang_tua_id');
    }
}
