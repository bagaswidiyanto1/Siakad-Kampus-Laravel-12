<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class JenisPengajuan extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'jenis_pengajuan';

    protected $fillable = [
        'kode',
        'nama',
        'deskripsi',
        'urutan_approval',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'urutan_approval' => 'integer',
    ];

    // Accessor
    public function getUrutanLabelAttribute()
    {
        $labels = [
            1 => 'Prodi',
            2 => 'Fakultas',
            3 => 'BAA',
        ];
        return $labels[$this->urutan_approval] ?? 'Unknown';
    }

    // Activity Log
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['kode', 'nama', 'deskripsi', 'urutan_approval', 'is_active'])
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "Jenis Pengajuan {$this->nama} telah di{$eventName}");
    }

    public function scopeAktif($query)
    {
        return $query->where('is_active', true);
    }
}
