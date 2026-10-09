<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Dosen extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'dosen';

    protected $fillable = [
        'user_id',
        'name',
        'nidn',
        'gelar_depan',
        'gelar_belakang',
        'spesialisasi',
        'jabatan_fungsional',
        'tanggal_masuk',
        'status',
        'foto',
        'fakultas_id',
        'prodi_id',
    ];

    protected $casts = [
        'tanggal_masuk' => 'date',
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

    // Relasi ke Kelas (yang diajarkan)
    public function kelas()
    {
        return $this->hasMany(Kelas::class, 'dosen_id');
    }

    // Accessor nama lengkap dengan gelar
    public function getNamaLengkapAttribute()
    {
        $nama = '';
        if ($this->gelar_depan) {
            $nama .= $this->gelar_depan . ' ';
        }
        $nama .= $this->user->name;
        if ($this->gelar_belakang) {
            $nama .= ', ' . $this->gelar_belakang;
        }
        return $nama;
    }

    // Accessor untuk tampilan singkat
    public function getNamaSingkatAttribute()
    {
        return $this->user->name . ($this->gelar_belakang ? ', ' . $this->gelar_belakang : '');
    }

    // Activity Log
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['nidn', 'gelar_depan', 'gelar_belakang', 'spesialisasi', 'jabatan_fungsional', 'status'])
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "Dosen {$this->user->name} telah di{$eventName}");
    }

    // Scope untuk dosen aktif
    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }
}
