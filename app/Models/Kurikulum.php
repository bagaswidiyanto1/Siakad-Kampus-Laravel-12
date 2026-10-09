<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Kurikulum extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'kurikulum';

    protected $fillable = [
        'kode',
        'nama',
        'tahun_berlaku',
        'prodi_id',
        'is_active',
        'deskripsi',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'tahun_berlaku' => 'integer',
    ];


    // Relasi ke Program Studi
    public function prodi()
    {
        return $this->belongsTo(ProgramStudi::class, 'prodi_id');
    }

    public function mataKuliah()
    {
        return $this->hasMany(MataKuliah::class, 'kurikulum_id');
    }

    // Activity Log
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['kode', 'nama', 'tahun_berlaku', 'prodi_id', 'is_active'])
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "Kurikulum {$this->nama} telah di{$eventName}");
    }

    public function getNamaLengkapAttribute()
    {
        return $this->nama . ' (' . $this->tahun_berlaku . ')';
    }
}
