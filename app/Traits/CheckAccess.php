<?php

namespace App\Traits;
use App\Models\RoleApplication;
use Illuminate\Support\Str;


trait CheckAccess
{
    // public $profile_image;
    public function check()
    {
        $url = str_replace('/', '.', request()->path());
        $cek = RoleApplication::join('menus', 'menus.id', '=', 'role_applications.menu_id')
            ->where('menus.route', $url)
            ->count();
        if ($cek === 0) {
            abort(403, 'Access denied');
        }
    }

    public function crud($name=""){
        $cek = RoleApplication::join('menus', 'menus.id', '=', 'role_applications.menu_id')
                                ->where('menus.name', $name)
                                ->where('role_applications.role_id', session()->get('role_id'));
        if($cek->count() == 1){
            $cek = $cek->first();
            $this->akses_menu = $cek->menu_id;
            $this->akses_insert = $cek->status_insert;
            $this->akses_update = $cek->status_update;
            $this->akses_delete = $cek->status_delete;
            $this->akses_export = $cek->status_export;
        }else{
            abort(403, 'Access denied');
        }
    }

    public function __construct()
    {
        // if(session()->get('role_id') == 1){
        //     $this->profile_image = "siswa/default-image.jpg";
        // }else if(session()->get('role_id') == 2){
        //     $get = TraitsGuru::where('nip', session()->get('store_username'))->select('image')->first();
        //     $this->profile_image = "guru/".$get->image;
        // }else if(session()->get('role_id') == 3){
        //     $get = TraitsSiswa::where('nisn', session()->get('store_username'))->select('image')->first();
        //     $this->profile_image = "siswa/".$get->image;
        // }
        
        // if (Str::startsWith(request()->path(), 'cms/')) {
        //     if(request()->path() == "/" || request()->path() == ""){

        //     }else{
        //         $this->check();
        //     }
        // }
    }
}