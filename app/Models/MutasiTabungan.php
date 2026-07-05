<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class MutasiTabungan extends Model
{
    use \App\Traits\Tenantable;
    use LogsActivity;

    protected $guarded = ['id'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "Mutasi Tabungan {$eventName}");
    }

    public function tabungan()
    {
        return $this->belongsTo(Tabungan::class);
    }
}
