<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class MataKuliah extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'mata_kuliah';

    protected $fillable = [
        'kode',
        'nama',
        'sks',
        'semester',
        'jenis',
        'prodi_id',
        'kurikulum_id',
        'deskripsi',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sks' => 'integer',
        'semester' => 'integer',
    ];

    // Relasi ke Program Studi
    public function prodi()
    {
        return $this->belongsTo(ProgramStudi::class, 'prodi_id');
    }

    // Relasi ke Kurikulum
    public function kurikulum()
    {
        return $this->belongsTo(Kurikulum::class, 'kurikulum_id');
    }



    // Activity Log
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['kode', 'nama', 'sks', 'semester', 'jenis', 'prodi_id', 'kurikulum_id', 'is_active'])
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "Mata Kuliah {$this->nama} telah di{$eventName}");
    }

    public function getNamaLengkapAttribute()
    {
        return $this->kode . ' - ' . $this->nama . ' (' . $this->sks . ' SKS)';
    }
}
