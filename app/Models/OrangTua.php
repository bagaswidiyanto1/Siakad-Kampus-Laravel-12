<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class OrangTua extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'orang_tua';

    protected $fillable = [
        'mahasiswa_id',
        'nama_ayah',
        'nama_ibu',
        'pekerjaan_ayah',
        'pekerjaan_ibu',
        'alamat_ortu',
        'no_hp_ortu',
    ];

    // Relasi ke Mahasiswa
    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'mahasiswa_id');
    }

    // Accessor
    public function getNamaOrtuLengkapAttribute()
    {
        $ayah = $this->nama_ayah ?? '-';
        $ibu = $this->nama_ibu ?? '-';
        return "Ayah: $ayah, Ibu: $ibu";
    }

    // Activity Log
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'nama_ayah',
                'nama_ibu',
                'pekerjaan_ayah',
                'pekerjaan_ibu',
                'alamat_ortu',
                'no_hp_ortu'
            ])
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "Data Orang Tua mahasiswa telah di{$eventName}");
    }
}
