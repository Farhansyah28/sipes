<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Semester extends Model
{
    use \App\Traits\Tenantable;
    protected $guarded = ['id'];

    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class);
    }
}
