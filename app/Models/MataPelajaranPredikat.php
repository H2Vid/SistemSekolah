<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MataPelajaranPredikat extends Model
{
    use HasFactory;

    protected $table = 'mata_pelajaran_predikat';

    protected $fillable = [
        'id_mata_pelajaran',
        'predikat',
        'keterangan',
        'nilai_min',
        'nilai_max',
    ];

}
