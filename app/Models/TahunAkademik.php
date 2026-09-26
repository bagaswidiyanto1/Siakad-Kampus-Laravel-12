<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class TahunAkademik extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'tahun_akademik';

    protected $fillable = [
        'kode',
        'nama',
        'semester',
        'tanggal_mulai',
        'tanggal_selesai',
        'is_active',
        'is_current',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_current' => 'boolean',
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    // Activity Log
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['kode', 'nama', 'semester', 'tanggal_mulai', 'tanggal_selesai', 'is_active', 'is_current'])
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "Tahun Akademik {$this->nama} telah di{$eventName}");
    }

    // Scope untuk mendapatkan tahun aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope untuk mendapatkan tahun berjalan
    public function scopeCurrent($query)
    {
        return $query->where('is_current', true);
    }

    // Accessor
    public function getNamaLengkapAttribute()
    {
        return $this->nama . ' - ' . ucfirst($this->semester);
    }

    // Boot method untuk menjaga hanya 1 yang aktif dan current
    protected static function booted()
    {
        static::saving(function ($model) {
            // Jika diaktifkan, nonaktifkan yang lain
            if ($model->is_active) {
                static::where('id', '!=', $model->id)
                    ->update(['is_active' => false]);
            }

            // Jika dijadikan current, set current yang lain false
            if ($model->is_current) {
                static::where('id', '!=', $model->id)
                    ->update(['is_current' => false]);
            }
        });
    }
}
