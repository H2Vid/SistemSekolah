<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    use HasFactory;

    protected $table = 'jadwal_pelajaran';

    protected $fillable = [
        'kode_kelas',
        'kode_mapel',
        'nip',
        'jam_mulai',
        'jam_selesai',
        'hari',
        'aktif',
        'semester_id',
        'tahun_ajaran',
    ];

}
