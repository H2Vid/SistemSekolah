<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modul extends Model
{
    use HasFactory;

    protected $table = 'modul';
    public static $FILE_PATH = 'modul';

    protected $fillable = [
        'id_jadwal',
        'kode_mapel',
        'nama_file',
        'file_upload',
        'tanggal_upload',
    ];

}
