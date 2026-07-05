<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Traits\Tenantable;

class Nilai extends Model
{
    use Tenantable, LogsActivity;

    protected $guarded = ['id'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll()->logOnlyDirty();
    }

    public function santri() { return $this->belongsTo(Santri::class); }
    public function jadwal() { return $this->belongsTo(Jadwal::class); }
}
