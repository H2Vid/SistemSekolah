<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasFactory;

    protected $table = 'absensi';
    public $timestamps = false;
    protected $fillable = [
        'id_jadwal',
        'pertemuan_ke',
        'kode_mapel',
        'nisn',
        'keterangan',
        'tanggal',
        'semester_id',
        'created_at',
    ];

}
