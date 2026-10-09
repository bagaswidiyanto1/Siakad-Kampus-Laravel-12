<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Kelas extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'kelas';

    protected $fillable = [
        'nama',
        'kapasitas',
        'mata_kuliah_id',
        'dosen_id',  // ini merujuk ke tabel dosen, bukan users
        'tahun_akademik_id',
        'ruang',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'kapasitas' => 'integer',
    ];

    // Relasi ke Mata Kuliah
    public function mataKuliah()
    {
        return $this->belongsTo(MataKuliah::class, 'mata_kuliah_id');
    }

    // Relasi ke Dosen (tabel dosen, bukan users)
    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'dosen_id');
    }

    // Relasi ke Tahun Akademik
    public function tahunAkademik()
    {
        return $this->belongsTo(TahunAkademik::class, 'tahun_akademik_id');
    }

    // Accessor nama lengkap kelas
    public function getNamaLengkapAttribute()
    {
        return $this->mataKuliah->nama ?? 'Kelas ' . $this->nama;
    }



    // Cek apakah kelas sudah diambil oleh mahasiswa tertentu
    public function isTakenByMahasiswa($mahasiswaId, $tahunAkademikId, $semester)
    {
        return $this->krsDetails()
            ->whereHas('krs', function ($q) use ($mahasiswaId, $tahunAkademikId, $semester) {
                $q->where('mahasiswa_id', $mahasiswaId)
                    ->where('tahun_akademik_id', $tahunAkademikId)
                    ->where('semester', $semester);
            })
            ->exists();
    }

    // Activity Log
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['nama', 'kapasitas', 'mata_kuliah_id', 'dosen_id', 'tahun_akademik_id', 'ruang', 'is_active'])
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "Kelas {$this->nama} telah di{$eventName}");
    }
}
