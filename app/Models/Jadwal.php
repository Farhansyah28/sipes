<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Traits\Tenantable;

class Jadwal extends Model
{
    use Tenantable, LogsActivity;

    protected $guarded = ['id'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll()->logOnlyDirty();
    }

    public function semester() { return $this->belongsTo(Semester::class); }
    public function kelas() { return $this->belongsTo(Kelas::class); }
    public function mataPelajaran() { return $this->belongsTo(MataPelajaran::class); }
    public function ustadz() { return $this->belongsTo(Ustadz::class); }
}
