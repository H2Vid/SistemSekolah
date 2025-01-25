<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;

    public static $FILE_PATH = 'store';

    protected $fillable = [
        'name',
        'phone',
        'address',
        'email',
        'image',
        'type',
        'store_id',
        'status',
    ];

}
