<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Santri extends Model
{
    use \App\Traits\Tenantable;
    use LogsActivity;

    protected $guarded = ['id'];

    protected static function booted()
    {
        static::saving(function ($model) {
            if ($model->isDirty('status') && $model->status === 'Aktif' && empty($model->nis)) {
                $year = date('y');
                $random = str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
                $model->nis = "NIS{$year}{$random}";
            }
        });
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }

    public function orangTua()
    {
        return $this->belongsTo(OrangTua::class);
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function kamar()
    {
        return $this->belongsTo(Kamar::class);
    }

    public function tabungan()
    {
        return $this->hasOne(Tabungan::class);
    }

    public function tagihans()
    {
        return $this->hasMany(Tagihan::class);
    }

    public function getTunggakanAttribute()
    {
        return $this->tagihans()->whereIn('status', ['Belum Bayar', 'Sebagian'])->sum('sisa_tagihan');
    }

    public function getTarikHariIniAttribute()
    {
        if (!$this->tabungan) return 0;
        return $this->tabungan->mutasiTabungans()
                    ->where('tipe', 'Tarik')
                    ->whereDate('tanggal', now()->format('Y-m-d'))
                    ->sum('nominal');
    }
}
