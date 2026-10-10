<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Mahasiswa extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'mahasiswa';

    protected $fillable = [
        'user_id',
        'nim',
        'nisn',
        'nik',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'agama',
        'status_pernikahan',
        'kewarganegaraan',
        'alamat_ktp',
        'alamat_domisili',
        'no_telepon',
        'no_hp',
        'email_pribadi',
        'foto',
        'angkatan',
        'status_mahasiswa',
        'fakultas_id',
        'prodi_id',
        'dosen_wali_id',
        'tgl_masuk',
        'tgl_lulus',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'tgl_masuk' => 'date',
        'tgl_lulus' => 'date',
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

    // Relasi ke Program Studi
    public function prodi()
    {
        return $this->belongsTo(ProgramStudi::class, 'prodi_id');
    }



    // Relasi ke Dosen Wali
    public function dosenWali()
    {
        return $this->belongsTo(Dosen::class, 'dosen_wali_id');
    }

    // Relasi ke Orang Tua
    public function orangTua()
    {
        return $this->hasOne(OrangTua::class, 'mahasiswa_id');
    }

    // Relasi ke Wali
    public function wali()
    {
        return $this->hasOne(Wali::class, 'mahasiswa_id');
    }

    // Relasi ke Riwayat Pendidikan
    public function riwayatPendidikan()
    {
        return $this->hasMany(RiwayatPendidikan::class, 'mahasiswa_id')->orderBy('tahun_masuk', 'desc');
    }

    // Accessor untuk nama lengkap
    public function getNamaLengkapAttribute()
    {
        return $this->user->name ?? 'Tidak Ada Nama';
    }

    // Accessor untuk umur
    public function getUmurAttribute()
    {
        if ($this->tanggal_lahir) {
            return $this->tanggal_lahir->age;
        }
        return null;
    }



    // Activity Log
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'nim',
                'nisn',
                'nik',
                'tempat_lahir',
                'tanggal_lahir',
                'jenis_kelamin',
                'agama',
                'status_pernikahan',
                'kewarganegaraan',
                'alamat_ktp',
                'alamat_domisili',
                'no_telepon',
                'no_hp',
                'email_pribadi',
                'angkatan',
                'status_mahasiswa',
                'fakultas_id',
                'prodi_id',
                'dosen_wali_id',
                'tgl_masuk',
                'tgl_lulus'
            ])
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "Mahasiswa {$this->user->name} telah di{$eventName}");
    }

    // Scope untuk mahasiswa aktif
    public function scopeAktif($query)
    {
        return $query->where('status_mahasiswa', 'aktif');
    }

    // Scope untuk mahasiswa berdasarkan fakultas
    public function scopeByFakultas($query, $fakultasId)
    {
        return $query->where('fakultas_id', $fakultasId);
    }

    // Scope untuk mahasiswa berdasarkan prodi
    public function scopeByProdi($query, $prodiId)
    {
        return $query->where('prodi_id', $prodiId);
    }
}
