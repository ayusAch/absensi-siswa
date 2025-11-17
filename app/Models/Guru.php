<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    use HasFactory;

    protected $table = 'gurus'; // Sesuai dengan nama tabel

    protected $fillable = [
        'nama_lengkap',
        'nip',
        'email',
        'no_telepon', 
        'alamat',
        'jenis_kelamin',
        'tanggal_lahir',
        'foto',
        'keterangan',
        'status'
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function kelas()
    {
        return $this->hasMany(Kelas::class, 'wali_kelas_id');
    }
}