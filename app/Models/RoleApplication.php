<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleApplication extends Model
{
    use HasFactory;

    protected $fillable = ['role_id', 'menu_id', 'status_insert', 'status_read', 'status_update', 'status_delete', 'status_export'];

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }
}
