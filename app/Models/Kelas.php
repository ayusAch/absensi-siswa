<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_kelas',
        'wali_kelas_id',
        'tahun_ajaran',
    ];

    // Relationship dengan Guru
    public function guru()
    {
        return $this->belongsTo(Guru::class, 'wali_kelas_id');
    }

    // Relationship dengan Siswa
    public function siswa()
    {
        return $this->hasMany(Siswa::class);
    }

    // Accessor untuk mendapatkan nama wali kelas
    public function getWaliKelasAttribute()
    {
        return $this->guru ? $this->guru->nama_lengkap : null;
    }
}