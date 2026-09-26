<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ProgramStudi extends Model
{
    use HasFactory, LogsActivity;
    protected $table = 'program_studi';

    protected $fillable = [
        'kode',
        'nama',
        'jenjang',
        'fakultas_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relasi ke Fakultas
    public function fakultas()
    {
        return $this->belongsTo(Fakultas::class);
    }

    // Relasi ke User (melalui prodi_id)
    public function users()
    {
        return $this->hasMany(User::class);
    }

    // Activity Log
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['kode', 'nama', 'jenjang', 'fakultas_id', 'is_active'])
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "Program Studi {$this->nama} telah di{$eventName}");
    }

    // Accessor
    public function getNamaLengkapAttribute()
    {
        return $this->jenjang . ' ' . $this->nama . ' (' . $this->kode . ')';
    }
}
