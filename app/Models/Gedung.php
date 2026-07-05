<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gedung extends Model
{
    use \App\Traits\Tenantable;
    protected $guarded = ['id'];
}
