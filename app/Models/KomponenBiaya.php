<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class KomponenBiaya extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'komponen_biaya';

    protected $fillable = [
        'kode',
        'nama',
        'jenis',
        'nominal',
        'semester_berlaku',
        'prodi_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'nominal' => 'decimal:2',
    ];

    // Relasi ke Program Studi
    public function prodi()
    {
        return $this->belongsTo(ProgramStudi::class, 'prodi_id');
    }


    // Activity Log
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['kode', 'nama', 'jenis', 'nominal', 'semester_berlaku', 'prodi_id', 'is_active'])
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "Komponen Biaya {$this->nama} telah di{$eventName}");
    }

    public function getNominalFormattedAttribute()
    {
        return 'Rp ' . number_format($this->nominal, 0, ',', '.');
    }
}
