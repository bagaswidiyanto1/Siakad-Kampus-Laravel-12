<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class SekolahAsal extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'sekolah_asal';

    protected $fillable = [
        'nama',
        'kota',
        'jenjang',
        'npsn',
        'alamat',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Accessor
    public function getNamaLengkapAttribute()
    {
        return $this->nama . ' (' . $this->kota . ')';
    }

    // Activity Log
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['nama', 'kota', 'jenjang', 'npsn', 'alamat', 'is_active'])
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "Sekolah Asal {$this->nama} telah di{$eventName}");
    }

    public function scopeAktif($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByJenjang($query, $jenjang)
    {
        return $query->where('jenjang', $jenjang);
    }
}
