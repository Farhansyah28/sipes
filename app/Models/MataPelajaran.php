<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MataPelajaran extends Model
{
    use \App\Traits\Tenantable;
    protected $guarded = ['id'];
}
