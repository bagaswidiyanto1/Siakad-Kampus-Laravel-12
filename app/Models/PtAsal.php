<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class PtAsal extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'pt_asal';

    protected $fillable = [
        'nama',
        'kota',
        'kode_pt',
        'jenis',
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

    public function getJenisLabelAttribute()
    {
        $labels = [
            'PTN' => 'PTN',
            'PTS' => 'PTS',
            'Politeknik' => 'Politeknik',
            'Akademi' => 'Akademi',
        ];
        return $labels[$this->jenis] ?? $this->jenis;
    }

    // Activity Log
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['nama', 'kota', 'kode_pt', 'jenis', 'is_active'])
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "PT Asal {$this->nama} telah di{$eventName}");
    }

    public function scopeAktif($query)
    {
        return $query->where('is_active', true);
    }
}
