<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory;

    public static $FILE_PATH = 'siswa';

    protected $table = 'siswa';
    protected $rules = [
        'nisn' => 'required|numeric',
    ];

    protected $fillable = [
        'nisn',
        'nama',
        'jenis_kelamin',
        'tanggal_lahir',
        'tempat_lahir',
        'agama',
        'no_hp',
        'email',
        'alamat',
        'nama_wali',
        'no_telepon_wali',
        'angkatan',
        'kode_kelas',
        'image',
        'status_siswa',
        'semester_id',
    ];
    public function semester()
    {
        return $this->belongsTo(Semester::class, 'semester_id', 'id');
    }
    public function isActive()
    {
        return $this->status_siswa === 'Aktif';
    }
}
