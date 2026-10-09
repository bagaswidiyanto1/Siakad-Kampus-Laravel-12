<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Staff extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'staff';

    protected $fillable = [
        'user_id',
        'nip',
        'jabatan',
        'fakultas_id',
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke Fakultas
    public function fakultas()
    {
        return $this->belongsTo(Fakultas::class, 'fakultas_id');
    }

    // Accessor
    public function getNamaLengkapAttribute()
    {
        return $this->user->name . ($this->jabatan ? ' (' . $this->jabatan . ')' : '');
    }

    // Activity Log
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['nip', 'jabatan', 'fakultas_id'])
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "Staff {$this->user->name} telah di{$eventName}");
    }
}
