<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kamar extends Model
{
    use \App\Traits\Tenantable;
    protected $guarded = ['id'];

    public function gedung()
    {
        return $this->belongsTo(Gedung::class);
    }
}
