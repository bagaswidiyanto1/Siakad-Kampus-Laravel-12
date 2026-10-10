<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Wali extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'wali';

    protected $fillable = [
        'mahasiswa_id',
        'nama_wali',
        'hubungan',
        'pekerjaan',
        'alamat',
        'no_hp',
    ];

    // Relasi ke Mahasiswa
    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'mahasiswa_id');
    }

    // Activity Log
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'nama_wali',
                'hubungan',
                'pekerjaan',
                'alamat',
                'no_hp'
            ])
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "Data Wali mahasiswa telah di{$eventName}");
    }
}
