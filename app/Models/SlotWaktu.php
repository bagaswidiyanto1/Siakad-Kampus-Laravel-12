<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class SlotWaktu extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'slot_waktu';

    protected $fillable = [
        'hari',
        'jam_mulai',
        'jam_selesai',
        'kode',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'jam_mulai' => 'datetime:H:i',
        'jam_selesai' => 'datetime:H:i',
    ];

    // Boot untuk generate kode otomatis
    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->kode)) {
                $hari = substr($model->hari, 0, 3);
                $mulai = date('Hi', strtotime($model->jam_mulai));
                $selesai = date('Hi', strtotime($model->jam_selesai));
                $model->kode = strtoupper($hari . '-' . $mulai . '-' . $selesai);
            }
        });
    }

    // Accessor
    public function getJamMulaiFormattedAttribute()
    {
        return date('H:i', strtotime($this->jam_mulai));
    }

    public function getJamSelesaiFormattedAttribute()
    {
        return date('H:i', strtotime($this->jam_selesai));
    }

    public function getWaktuLengkapAttribute()
    {
        return $this->hari . ' ' . $this->jam_mulai_formatted . ' - ' . $this->jam_selesai_formatted;
    }

    // Activity Log
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['hari', 'jam_mulai', 'jam_selesai', 'kode', 'is_active'])
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "Slot Waktu {$this->kode} telah di{$eventName}");
    }

    public function scopeAktif($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByHari($query, $hari)
    {
        return $query->where('hari', $hari);
    }
}
