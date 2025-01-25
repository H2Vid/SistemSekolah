<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nilai extends Model
{
    use HasFactory;

    protected $table = 'nilai';

    protected $fillable = [
        'id_jadwal',
        'kode_mapel',
        'nisn',
        'ph1',
        'ph2',
        'uts',
        'uas',
        'kkm_keterampilan',
        'nilai_keterampilan',
        'predikat_keterampilan',
        'deskripsi_keterampilan',
        'sikap',
        'kedisiplinan',
        'kebersihan',
        'semester_id',
        'tahun_ajaran'
    ];
    public function jadwalPelajaran()
    {
        return $this->belongsTo(JadwalPelajaran::class, 'tahun_ajaran', 'tahun_ajaran');
    }
}
