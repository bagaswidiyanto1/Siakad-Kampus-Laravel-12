<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Ruang extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'ruang';

    protected $fillable = [
        'kode',
        'nama',
        'gedung',
        'kapasitas',
        'jenis',
        'fasilitas',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'kapasitas' => 'integer',
    ];

    // Accessor
    public function getNamaLengkapAttribute()
    {
        return $this->nama . ' (' . $this->gedung . ')';
    }

    public function getKapasitasFormattedAttribute()
    {
        return $this->kapasitas . ' orang';
    }

    public function getJenisLabelAttribute()
    {
        $labels = [
            'kelas' => 'Kelas',
            'lab' => 'Laboratorium',
            'auditorium' => 'Auditorium',
            'ruang_seminar' => 'Ruang Seminar',
        ];
        return $labels[$this->jenis] ?? $this->jenis;
    }

    // Activity Log
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['kode', 'nama', 'gedung', 'kapasitas', 'jenis', 'is_active'])
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "Ruang {$this->nama} telah di{$eventName}");
    }

    public function scopeAktif($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByJenis($query, $jenis)
    {
        return $query->where('jenis', $jenis);
    }
}
