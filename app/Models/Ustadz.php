<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ustadz extends Model
{
    use \App\Traits\Tenantable;
    protected $guarded = ['id'];
}
