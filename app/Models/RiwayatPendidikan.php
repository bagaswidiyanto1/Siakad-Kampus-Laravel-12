<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class RiwayatPendidikan extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'riwayat_pendidikan';

    protected $fillable = [
        'mahasiswa_id',
        'jenjang',
        'nama_institusi',
        'kota',
        'tahun_masuk',
        'tahun_lulus',
        'jurusan',
        'nilai_akhir',
    ];

    protected $casts = [
        'tahun_masuk' => 'integer',
        'tahun_lulus' => 'integer',
    ];

    // Relasi ke Mahasiswa
    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'mahasiswa_id');
    }

    // Accessor
    public function getTahunAttribute()
    {
        $masuk = $this->tahun_masuk ?? '?';
        $lulus = $this->tahun_lulus ?? 'Sekarang';
        return "$masuk - $lulus";
    }

    // Activity Log
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'jenjang',
                'nama_institusi',
                'kota',
                'tahun_masuk',
                'tahun_lulus',
                'jurusan',
                'nilai_akhir'
            ])
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "Riwayat Pendidikan mahasiswa telah di{$eventName}");
    }
}
