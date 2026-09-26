<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Fakultas extends Model
{
    use HasFactory, LogsActivity;
    protected $fillable = [
        'kode',
        'nama',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    // Activity Log
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['kode', 'nama', 'is_active'])
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "Fakultas {$this->nama} telah di{$eventName}");
    }

    // Accessor untuk menampilkan nama lengkap
    public function getNamaLengkapAttribute()
    {
        return $this->kode . ' - ' . $this->nama;
    }
}
