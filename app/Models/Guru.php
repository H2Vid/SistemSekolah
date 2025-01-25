<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    use HasFactory;

    public static $FILE_PATH = 'guru';

    protected $table = 'guru';

    protected $fillable = [
        'nip',
        'nama',
        'jenis_kelamin',
        'tanggal_lahir',
        'tempat_lahir',
        'agama',
        'no_hp',
        'email',
        'alamat',
        'image',
        'status_guru',
    ];
    public function isActive()
    {
        return $this->status_guru === 'Aktif';
    }
}
